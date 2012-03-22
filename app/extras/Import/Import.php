<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
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
		debug($this->savedVariables);
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
					$s = new $serviceName($x['id']);
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
		Service::preventFlush();
		while($x = mysql_fetch_array($r)) {
			$s = new D\LanguageService();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->supported = (bool)$x['translated'];
			debug($s);
			$s->defaultCollation = $x['default_collation'];
			$s->details = json_encode(explode2Levels(';', ':', $x['attributes']));
			$s->save();

		}
		Service::flush(FALSE);

		$this->createPhrasesByOld('\Dictionary\Language', 'name', 'supportedLanguages', 'ACTIVE', 'languages', 'name_dic_id');
		Service::flush(FALSE);
		$this->savedVariables['importedSections']['languages'] = 2;
	}

	public function importCurrencies() {
		$this->savedVariables['importedSections']['currencies'] = 1;
		$r = q('select * from currencies order by id');
		Service::preventFlush();
		while($x = mysql_fetch_array($r)) {
			$s = new S\CurrencyService();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->exchangeRate = (bool)$x['exchange_rate'];
			$s->decimalPlaces = $x['decimal_places'];
			$s->rounding = $x['decimal_places'];

			$s->save();

		}

		Service::flush(FALSE);

		$this->createPhrasesByOld('\Currency', 'name', 'supportedLanguages', 'ACTIVE', 'currencies', 'name_dic_id');
		Service::flush(FALSE);

		$this->savedVariables['importedSections']['currencies'] = 2;
	}

	public function importDomains() {
		$this->savedVariables['importedSections']['domains'] = 1;
		$r = q('select domain from countries where length(domain)>0');
		Service::preventFlush();
		while($x = mysql_fetch_array($r)) {
			$s = new S\DomainService();
			$s->domain = $x['domain'];
			$s->save();
		}

		$s = new S\DomainService();
		$s->domain = 'tralandia.com';
		$s->save();
		Service::flush(FALSE);
		$this->savedVariables['importedSections']['domains'] = 2;
	}

	public function importLocations() {
		$this->savedVariables['importedSections']['locations'] = 1;
		$r = q('select domain from continents order by id');
		Service::preventFlush();
		while($x = mysql_fetch_array($r)) {
			$dictionaryType = new D\TypeService();
			$dictionaryType->entityName = '\Location\Location';
			$dictionaryType->entityAttribute = 'name';
			$dictionaryType->requiredLanguages = 'incomingLanguages';
			$dictionaryType->translationLevelRequirement = \Entities\Dictionary\Type::TRANSLATION_LEVEL_NATIVE;
			$dictionaryType->save();

			$s = new S\Location\LocationService();
			$s->name = createNewPhrase($dictionaryType, $x['name_dic_id']);
			//$s->slug = new \Extras\Types\Slug();
			$s->save();
		}

		Service::flush(FALSE);
		$this->savedVariables['importedSections']['locations'] = 2;
	}

	public function importCompanies() {
		$this->savedVariables['importedSections']['companies'] = 1;
		$r = q('select * from companies order by id');
		Service::preventFlush();
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
			$s->details = '';
			$s->save();

		}
		Service::flush(FALSE);
		
		$this->createPhrasesByOld('\Company\Company', 'registrator', 'supportedLanguages', 'ACTIVE', 'companies', 'registrator_dic_id');
		Service::flush(FALSE);
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
		$phrase->details = '';
		$phrase->save();

		$allLanguages = q('SHOW tables like "z_%"');
		while($table = mysql_fetch_array($allLanguages)) {
			$oldTranslation = qf('select * from '.$table[0].' where id = '.$oldPhraseId);
			if (!$oldTranslation || strlen($oldTranslation['text']) == 0) continue;

			$translation = new \Services\Dictionary\TranslationService;

			$newEntityId = getByOldId('\Dictionary\Language', qc('select id from languages where iso = "'.substr($table[0], 2).'"'));

			$translation->language = \Services\Dictionary\LanguageService::get($newEntityId);
			$translation->translation = $oldTranslation['text'];
			
			$translation->translated = fromStamp($oldTranslation['updated']);
			$translation->variations = '';
			$translation->variationsPending = '';
			$translation->save();

			$phrase->addTranslation($translation);
		}

		$phrase->save();
		//$phrase->getEntityManager()->flush();
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
			$phrase->entityId = $s->id;
			$phrase->type = $dictionaryType;
			$phrase->details = '';
			$phrase->save();

			if ($phrase instanceof D\PhraseService) {
				$translation = new \Services\Dictionary\TranslationService();
				$translation->language = $textLanguage;
				$translation->translation = $x[$oldAttribute];
				
				$translation->translated = new \Nette\DateTime();
				$translation->variations = '';
				$translation->variationsPending = '';

				$translation->save();

				$phrase->addTranslation($translation);
				$phrase->save();
			}
		}
		return TRUE;
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