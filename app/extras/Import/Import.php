<?php

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class Import {
	public static $updateDateTime = NULL;

	public $sections = array();

	public $savedVariables = array();

	public function __construct() {
		$this->setSections();
		$this->loadVariables();
	}

	public function truncateDatabase() {
		qNew('SET FOREIGN_KEY_CHECKS = 0;');
		$allTables = qNew('SHOW tables');
		while ($table = mysql_fetch_array($allTables)) {
			qNew('truncate table '.$table[0]);
		}
		qNew('SET FOREIGN_KEY_CHECKS = 1;');
		foreach ($this->savedVariables['importedSections'] as $key => $value) {
			$this->savedVariables['importedSections'][$key] = 0;
		}
		$this->saveVariables();
	}

	public function dropAllTables() {
		qNew('SET FOREIGN_KEY_CHECKS = 0;');
		$allTables = qNew('SHOW tables');
		while ($table = mysql_fetch_array($allTables)) {
			qNew('drop table '.$table[0]);
		}
		qNew('SET FOREIGN_KEY_CHECKS = 1;');
		foreach ($this->savedVariables['importedSections'] as $key => $value) {
			$this->savedVariables['importedSections'][$key] = 0;
		}
		$this->saveVariables();
	}

	public function undoSection($section) {
		$tempSections = array_reverse($this->sections);
		foreach ($tempSections as $key => $value) {
			foreach ($value as $key2 => $value2) {
				$tableName = str_replace('\\', '_', $key2);
				$tableName = trim($tableName, '_');
				$tableName = strtolower($tableName);

				$r = qNew('select id from '.$tableName.' order by id DESC');
				while ($x = mysql_fetch_array($r)) {
					$serviceName = '\Services'.$key2.'Service';
					debug($serviceName);
					$s = new $serviceName($x['id']);
					debug($s);
					$s->delete();
				}
			}
			$this->savedVariables['importedSections'][$key]=0;
			if ($key == $section) break;
		}
		$this->saveVariables();
	}

	public function importLanguages() {
		$this->savedVariables['importedSections']['languages'] = 1;
		$r = q('select * from languages order by id');
		while($x = mysql_fetch_array($r)) {
			$s = new D\LanguageService();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->supported = (bool)$x['translated'];
			$s->defaultCollation = $x['default_collation'];
			$s->details = json_encode(explode2Levels(';', ':', $x['attributes']));

			$s->save();

		}
		$s->getEntityManager()->flush();

		$this->createPhrasesByOld('\Dictionary\Language', 'name', 'supportedLanguages', 'ACTIVE', 'languages', 'name_dic_id');
		$s->getEntityManager()->flush();
		$this->savedVariables['importedSections']['languages'] = 2;
	}

	public function importCurrencies() {
		$this->savedVariables['importedSections']['currencies'] = 1;
		$dictionaryType = $this->createDictionaryType('\Currency', 'name', 'supportedLanguages', 'ACTIVE');

		$r = q('select * from currencies order by id');
		while($x = mysql_fetch_array($r)) {
			$s = new S\CurrencyService();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->name = $this->createNewPhrase($dictionaryType, $x['name_dic_id']);
			$s->exchangeRate = (bool)$x['exchange_rate'];
			$s->decimalPlaces = $x['decimal_places'];
			$s->rounding = $x['decimal_places'];

			$s->save();

		}

		$s->getEntityManager()->flush();
		$this->savedVariables['importedSections']['currencies'] = 2;
	}

	public function importDomains() {
		$this->savedVariables['importedSections']['domains'] = 1;
		$r = q('select domain from countries where length(domain)>0');
		while($x = mysql_fetch_array($r)) {
			$s = new S\DomainService();
			$s->domain = $x['domain'];
			$s->save();
		}

		$s = new S\DomainService();
		$s->domain = 'tralandia.com';
		$s->save();
		$s->getEntityManager()->flush();
		$this->savedVariables['importedSections']['domains'] = 2;
	}

	public function importLocations() {
		$this->savedVariables['importedSections']['locations'] = 1;
		$locationType = new S\Location\TypeService();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'incomingLanguages', 'NATIVE', 'continent', getLangByIso('en'));
		$locationType->details = new \Extras\Types\Json("[]");;
		$locationType->save();

		$dictionaryType = new D\TypeService();
		$dictionaryType->entityName = '\Location\Location';
		$dictionaryType->entityAttribute = 'name';
		$dictionaryType->requiredLanguages = 'incomingLanguages';
		$dictionaryType->translationLevelRequirement = \Entities\Dictionary\Type::TRANSLATION_LEVEL_NATIVE;
		$dictionaryType->save();

		$r = q('select * from continents order by id');
		while($x = mysql_fetch_array($r)) {
			$s = new S\Location\LocationService();
			$s->name = $this->createNewPhrase($dictionaryType, $x['name_dic_id']);
			//$s->iso = 'iso';
			$s->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$s->type = $locationType;
			//$s->polygon = new \Extras\Types\Json("[]");
			//$s->latitude = new \Extras\Types\Latlong("[]");
			//$s->longitude = new \Extras\Types\Latlong("[]");
			//$s->defaultZoom = 2;
			$s->save();
		}

		$s->getEntityManager()->flush();
		$this->savedVariables['importedSections']['locations'] = 2;
	}

	public function importCompanies() {
		$this->savedVariables['importedSections']['companies'] = 1;
		$r = q('select * from companies order by id');
		while($x = mysql_fetch_array($r)) {
			$s = new S\Company\CompanyService();
			$s->oldId = $x['id'];
			$s->name = $x['name'];
			// @todo - prerobit, ked budeme mat objekt Address()
			$s->address = json_encode(array(
				'address' => array_filter(array($x['address'], $x['address2'])),
				'postcode' => $x['postcode'],
				'locality' => $x['locality'],
			));
			$s->companyId = $x['company_id'];
			$s->companyVatId = $x['company_vat_id'];
			$s->vat = $x['vat'];

			// @todo country treba pridat do adresy podla dohody....

			// @todo toto treba robit az potom, co budem mat locations naimportovane
			// $countries = getNewIds('\Company\Company', $x['for_countries_ids']);
			// foreach ($countries as $key => $value) {
			// 	$s->addCountry(new S\Location\LocationService($value));
			// }
			$s->details = new \Extras\Types\Json("[]");
			$s->save();

		}
		$s->getEntityManager()->flush();
		
		$this->createPhrasesByOld('\Company\Company', 'registrator', 'supportedLanguages', 'ACTIVE', 'companies', 'registrator_dic_id');
		$s->getEntityManager()->flush();
		$this->savedVariables['importedSections']['companies'] = 2;
	}

	private function createPhrasesByOld($entityName, $entityAttribute, $requiredLanguages, $level, $oldTableName, $oldAttribute) {
		eval('$level = \Entities\Dictionary\Type::TRANSLATION_LEVEL_'.strtoupper($level).';');
		$dictionaryType = new D\TypeService();
		$dictionaryType->entityName = $entityName;
		$dictionaryType->entityAttribute = $entityAttribute;
		$dictionaryType->requiredLanguages = $requiredLanguages;
		$dictionaryType->translationLevelRequirement = $level;
		$dictionaryType->save();

		$r = q('select * from '.$oldTableName.' order by id');
		while($x = mysql_fetch_array($r)) {
			if ($x[$oldAttribute]) {
				$newEntityId = getByOldId($entityName, $x['id']);
				$phrase = $this->createNewPhrase($dictionaryType, $x[$oldAttribute]);
				if ($phrase instanceof D\PhraseService) {
					eval('$s = new \Services'.$entityName.'Service('.$newEntityId.');');
					$s->{$entityAttribute} = $phrase;
					$s->save();						
				}
			}
		}
	}

	private function createNewPhrase(\Services\Dictionary\TypeService $type, $oldPhraseId) {
		$oldPhraseData = qf('select * from dictionary where id = '.$oldPhraseId);
		if (!$oldPhraseData) return FALSE;

		$phrase = new \Services\Dictionary\PhraseService();
		$phrase->ready = (bool)$oldPhraseData['ready'];
		$phrase->type = $type;
		$phrase->details = new \Extras\Types\Json("[]");
		$phrase->save();

		$allLanguages = q('SHOW tables like "z_%"');
		while($table = mysql_fetch_array($allLanguages)) {
			$oldTranslation = qf('select * from '.$table[0].' where id = '.$oldPhraseId);
			if (!$oldTranslation || strlen($oldTranslation['text']) == 0) continue;

			$translation = new \Services\Dictionary\TranslationService;

			$newEntityId = getByOldId('\Dictionary\Language', qc('select id from languages where iso = "'.substr($table[0], 2).'"'));

			$translation->language = new \Services\Dictionary\LanguageService($newEntityId);
			$translation->translation = $oldTranslation['text'];
			
			$translation->translated = fromStamp($oldTranslation['updated']);
			$translation->variations = new \Extras\Types\Json("[]");
			$translation->variationsPending = new \Extras\Types\Json("[]");

			$translation->save();

			$phrase->addTranslation($translation);
		}

		$phrase->save();
		//$phrase->getEntityManager()->flush();
		return $phrase;
	}

	private function createPhraseFromString($entityName, $entityAttribute, $requiredLanguages, $level, $text, $textLanguage) {

		eval('$level = \Entities\Dictionary\Type::TRANSLATION_LEVEL_'.strtoupper($level).';');
		$dictionaryType = new D\TypeService();
		$dictionaryType->entityName = $entityName;
		$dictionaryType->entityAttribute = $entityAttribute;
		$dictionaryType->requiredLanguages = $requiredLanguages;
		$dictionaryType->translationLevelRequirement = $level;
		$dictionaryType->save();

		$phrase = new \Services\Dictionary\PhraseService();
		$phrase->ready = TRUE;
		$phrase->type = $dictionaryType;
		$phrase->details = new \Extras\Types\Json("[]");

		if ($phrase instanceof D\PhraseService) {
			$translation = new \Services\Dictionary\TranslationService();
			$translation->language = $textLanguage;
			$translation->translation = $text;
			
			$translation->translated = new \Nette\DateTime();
			$translation->variations = new \Extras\Types\Json("[]");
			$translation->variationsPending = new \Extras\Types\Json("[]");

			$translation->save();

			$phrase->addTranslation($translation);
		}

		$phrase->save();
		return $phrase;
	}

	private function createPhrasesFromString($entityName, $entityAttribute, $requiredLanguages, $level, $oldTableName, $oldAttribute, $textLanguage) {

		eval('$level = \Entities\Dictionary\Type::TRANSLATION_LEVEL_'.strtoupper($level).';');
		$dictionaryType = new D\TypeService();
		$dictionaryType->entityName = $entityName;
		$dictionaryType->entityAttribute = $entityAttribute;
		$dictionaryType->requiredLanguages = $requiredLanguages;
		$dictionaryType->translationLevelRequirement = $level;
		$dictionaryType->save();

		$r = q('select * from '.$oldTableName.' order by id');
		while($x = mysql_fetch_array($r)) {
			$phrase = new \Services\Dictionary\PhraseService();
			$phrase->ready = TRUE;
			$phrase->type = $dictionaryType;
			$phrase->details = new \Extras\Types\Json("[]");
			$phrase->save();

			if ($phrase instanceof D\PhraseService) {
				$translation = new \Services\Dictionary\TranslationService();
				$translation->language = $textLanguage;
				$translation->translation = $x[$oldAttribute];
				
				$translation->translated = new \Nette\DateTime();
				$translation->variations = new \Extras\Types\Json("[]");
				$translation->variationsPending = new \Extras\Types\Json("[]");

				$translation->save();

				$phrase->addTranslation($translation);
				$phrase->save();
			}
		}
		return TRUE;
	}

	private function createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level) {

		eval('$level = \Entities\Dictionary\Type::TRANSLATION_LEVEL_'.strtoupper($level).';');
		$dictionaryType = new D\TypeService();
		$dictionaryType->entityName = $entityName;
		$dictionaryType->entityAttribute = $entityAttribute;
		$dictionaryType->requiredLanguages = $requiredLanguages;
		$dictionaryType->translationLevelRequirement = $level;
		$dictionaryType->save();

		return $dictionaryType;
	}

	private function setSections() {
		$this->sections = array(
			'languages' => array(
				'\Dictionary\Language' => array(),
			),
			'currencies' => array(
				'\Currency' => array(),
			),
			'domains' => array(
				'\Domain' => array(),
			),
			'locations' => array(
				'\Location\Location' => array(),
				'\Location\Type' => array(),
			),
			'companies' => array(
				'\Company\Company' => array(),
			),
		);
	}

	public function getSections() {
		return $this->sections;
	}

	public function loadVariables() {
		$t = qc('select value from _importVariables where id = 1');
		$this->savedVariables = \Nette\Utils\Json::decode($t, TRUE);
	}

	public function saveVariables() {
		q('update _importVariables set value ="'.mysql_real_escape_string(\Nette\Utils\Json::encode($this->savedVariables)).'"  where id = 1');
	}
}