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
		q('truncate table _idPairs');
		qNew('SET FOREIGN_KEY_CHECKS = 1;');
		$this->savedVariables['importedSections'] = array();
		$this->saveVariables();
	}

	public function undoSection($section) {
		$tempSections = array_reverse($this->sections);
		foreach ($tempSections as $key => $value) {
			foreach ($value as $key2 => $value2) {
				$tableName = str_replace('\\', '_', $key2);
				$tableName = trim($tableName, '_');
				$tableName = strtolower($tableName);
				debug($tableName);

				$r = qNew('select id from '.$tableName.' order by id DESC');
				while ($x = mysql_fetch_array($r)) {
					$serviceName = '\Services'.$key2.'Service';
					debug($serviceName.':'.$x['id']);
					$s = new $serviceName($x['id']);
					$s->delete();
				}
				q('delete from table _idPairs where entity = "'.mysql_real_escape_string($key2).'"');
			}
			unset($this->savedVariables['importedSections'][$key]);
			if ($key == $section) break;
		}
		$this->saveVariables();
	}

	public function importLanguages() {
		$this->savedVariables['importedSections']['languages']['started'] = 1;
		$r = q('select * from languages order by id');
		while($x = mysql_fetch_array($r)) {
			$s = new D\LanguageService();
			$s->iso = $x['iso'];
			$s->supported = (bool)$x['translated'];
			$s->defaultCollation = $x['default_collation'];
			$s->details = json_encode(explode2Levels(';', ':', $x['attributes']));

			$s->save();

			addIdPair('languages', $x['id'], '\Dictionary\Language', $s->id);
		}
		$this->createPhrasesByOld('\Dictionary\Language', 'name', 'supportedLanguages', 'ACTIVE', 'languages', 'name_dic_id');
		$this->savedVariables['importedSections']['languages']['ended'] = 1;
	}

	public function importCurrencies() {
		$this->savedVariables['importedSections']['currencies']['started'] = 1;
		$r = q('select * from currencies order by id');
		while($x = mysql_fetch_array($r)) {
			$s = new S\CurrencyService();
			$s->iso = $x['iso'];
			$s->exchangeRate = (bool)$x['exchange_rate'];
			$s->decimalPlaces = $x['decimal_places'];
			$s->rounding = $x['decimal_places'];

			$s->save();

			addIdPair('currencies', $x['id'], '\Currency', $s->id);
		}

		$this->createPhrasesByOld('\Currency', 'name', 'supportedLanguages', 'ACTIVE', 'currencies', 'name_dic_id');
		$this->savedVariables['importedSections']['currencies']['ended'] = 1;
	}

	public function importDomains() {
		$this->savedVariables['importedSections']['domains']['started'] = 1;
		$r = q('select domain from countries where length(domain)>0');
		while($x = mysql_fetch_array($r)) {
			$s = new S\DomainService();
			$s->domain = $x['domain'];
			$s->save();
		}

		$s = new S\DomainService();
		$s->domain = 'tralandia.com';
		$s->save();
		$this->savedVariables['importedSections']['domains']['ended'] = 1;
	}

	public function importCompanies() {
		$this->savedVariables['importedSections']['companies']['started'] = 1;
		$r = q('select * from companies order by id');
		while($x = mysql_fetch_array($r)) {
			$s = new S\Company\CompanyService();
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

			addIdPair('companies', $x['id'], '\Company\Company', $s->id);
		}

		$this->createPhrasesByOld('\Company\Company', 'registrator', 'supportedLanguages', 'ACTIVE', 'companies', 'registrator_dic_id');
		$this->savedVariables['importedSections']['companies']['ended'] = 1;
	}

	private function createPhrasesByOld($entityName, $entityAttribute, $requiredLanguages, $level, $oldTableName, $oldAttribute) {
		debug(Debugger::timer());
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
				$newEntityId = getNewId($entityName, $x['id']);
				$phrase = $this->createNewPhrase($dictionaryType, $newEntityId, $x[$oldAttribute]);
				if ($phrase instanceof D\PhraseService) {
					debug($newEntityId);
					eval('$s = new \Services'.$entityName.'Service('.$newEntityId.');');
					debug($s);
					$s->{$entityAttribute} = $phrase;
					$s->save();
				}			
			}
		}
	}

	private function createNewPhrase(\Services\Dictionary\TypeService $type, $entityId, $oldPhraseId) {
		$oldPhraseData = qf('select * from dictionary where id = '.$oldPhraseId);
		if (!$oldPhraseData) return FALSE;

		$phrase = new \Services\Dictionary\PhraseService();
		$phrase->ready = (bool)$oldPhraseData['ready'];
		$phrase->entityId = (int)$entityId;
		$phrase->type = $type;
		$phrase->details = new \Extras\Types\Json("[]");
		$phrase->save();

		$allLanguages = q('SHOW tables like "z_%"');
		while($table = mysql_fetch_array($allLanguages)) {
			$oldTranslation = qf('select * from '.$table[0].' where id = '.$oldPhraseId);
			if (!$oldTranslation || strlen($oldTranslation['text']) == 0) continue;

			$translation = new \Services\Dictionary\TranslationService();
			$languageId = getNewId('\Dictionary\Language', qc('select id from languages where iso = "'.substr($table[0], 2).'"'));

			$translation->language = new \Services\Dictionary\LanguageService($languageId);
			$translation->translation = $oldTranslation['text'];
			
			$translation->translated = fromStamp($oldTranslation['updated']);
			$translation->variations = new \Extras\Types\Json("[]");
			$translation->variationsPending = new \Extras\Types\Json("[]");

			self::$updateDateTime = fromStamp($oldTranslation['updated']);
			$translation->save();

			$phrase->addTranslation($translation);
		}
		self::$updateDateTime = NULL;

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
			$phrase->entityId = $s->id;
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
			'companies' => array(
				'\Company\Company' => array(),
			),
		);
	}

	public function getSections() {
		return $this->sections;
	}

	private function loadVariables() {
		$this->savedVariables = json_decode(qc('select value from _importVariables where id = 1'));
	}

	private function saveVariables() {
		q('update _importVariables set value ="'.mysql_real_escape_string(json_encode($this->savedVariables)).'"  where id = 1');
	}
}