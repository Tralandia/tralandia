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
					$s = $serviceName::get($x['id']);
					$s->delete();
				}
			}
			$this->savedVariables['importedSections'][$key]=0;
			if ($key == $section) break;
		}
		Service::flush(FALSE);
		$this->saveVariables();
	}

	public function importLanguages() {
		\Extras\Models\Service::preventFlush();
		$this->savedVariables['importedSections']['languages'] = 1;
		$r = q('select * from languages order by id');
		while($x = mysql_fetch_array($r)) {
			$s = D\LanguageService::get();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->supported = (bool)$x['translated'];
			$s->defaultCollation = $x['default_collation'];
			$s->details = explode2Levels(';', ':', $x['attributes']);
			$s->save();

		}
		Service::flush(FALSE);

		$this->createPhrasesByOld('\Dictionary\Language', 'name', 'supportedLanguages', 'ACTIVE', 'languages', 'name_dic_id');		
		$this->savedVariables['importedSections']['languages'] = 2;
	}

	public function importCurrencies() {
		$this->savedVariables['importedSections']['currencies'] = 1;
		$dictionaryType = $this->createDictionaryType('\Currency', 'name', 'supportedLanguages', 'ACTIVE');

		$r = q('select * from currencies order by id');
		while($x = mysql_fetch_array($r)) {
			$s = S\CurrencyService::get();
			$s->oldId = $x['id'];
			$s->iso = $x['iso'];
			$s->name = $this->createNewPhrase($dictionaryType, $x['name_dic_id']);
			$s->exchangeRate = $x['exchange_rate'];
			$s->decimalPlaces = $x['decimal_places'];
			$s->rounding = $x['decimal_places'];

			$s->save();
		}
		$this->savedVariables['importedSections']['currencies'] = 2;
	}

	public function importDomains() {
		$this->savedVariables['importedSections']['domains'] = 1;
		$r = q('select domain from countries where length(domain)>0');
		while($x = mysql_fetch_array($r)) {
			$s = S\DomainService::get();
			$s->domain = $x['domain'];
			$s->save();
		}

		$s = S\DomainService::get();
		$s->domain = 'tralandia.com';
		$s->save();

		$this->savedVariables['importedSections']['domains'] = 2;
	}

	public function importLocations() {
		$this->savedVariables['importedSections']['locations'] = 1;

		$language = getLangByIso('en');
		$locationType = S\Location\TypeService::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'incomingLanguages', 'NATIVE', 'continent', $language);
		$locationType->save();

		$dictionaryType = $this->createDictionaryType('\Location\Location', 'name', 'incomingLanguages', 'NATIVE');

		$r = q('select * from continents order by id');
		while($x = mysql_fetch_array($r)) {
			$s = S\Location\LocationService::get();
			$s->name = $this->createNewPhrase($dictionaryType, $x['name_dic_id']);
			$s->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$s->type = $locationType;
			$s->save();
		}

		Service::flush(FALSE);


		$this->savedVariables['importedSections']['locations'] = 2;
	}

	public function importCompanies() {
		$this->savedVariables['importedSections']['companies'] = 1;
		$r = q('select * from companies order by id');

		$dictionaryTypeRegistrator = $this->createDictionaryType('\Company\Company', 'registrator', 'supportedLanguages', 'ACTIVE');

		while($x = mysql_fetch_array($r)) {
			$s = S\Company\CompanyService::get();
			$s->oldId = $x['id'];
			$s->name = $x['name'];

			$s->address = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['address'], $x['address2'])),
				'postcode' => $x['postcode'],
				'locality' => $x['locality'],
				'country' => getByOldId('\Location\Location', $x['locality']),
			));
			$s->companyId = $x['company_id'];
			$s->companyVatId = $x['company_vat_id'];
			$s->vat = $x['vat'];
			$s->registrator = $this->createNewPhrase(dictionaryTypeRegistrator, $x['registrator_dic_id']);

			$countries = getNewIds('\Company\Company', $x['for_countries_ids']);
			foreach ($countries as $key => $value) {
				$s->addCountry(S\Location\LocationService::get($value));
			}

			$s->save();
		}
	
		$this->savedVariables['importedSections']['companies'] = 2;
	}

	private function createPhrasesByOld($entityName, $entityAttribute, $requiredLanguages, $level, $oldTableName, $oldAttribute) {
		$dictionaryType = $this->createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level);

		$r = q('select * from '.$oldTableName.' order by id');
		while($x = mysql_fetch_array($r)) {
			if ($x[$oldAttribute]) {
				$newEntityId = getByOldId($entityName, $x['id']);
				$phrase = $this->createNewPhrase($dictionaryType, $x[$oldAttribute]);
				if ($phrase instanceof D\PhraseService) {
					eval('$s = \Services'.$entityName.'Service::get('.$newEntityId.');');
					if ($s->id > 0) {
						$s->{$entityAttribute} = $phrase;
						$s->save();						
					} else {
						debug($s);
						debug($newEntityId); return;	
					}
				}
			}
		}
	}

	private function createNewPhrase(\Services\Dictionary\TypeService $type, $oldPhraseId) {
		$oldPhraseData = qf('select * from dictionary where id = '.$oldPhraseId);
		if (!$oldPhraseData) return FALSE;

		$phrase = \Services\Dictionary\PhraseService::get();
		$phrase->ready = (bool)$oldPhraseData['ready'];
		$phrase->type = $type;
		$phrase->save();

		$allLanguages = q('SHOW tables like "z_%"');
		while($table = mysql_fetch_array($allLanguages)) {
			$oldTranslation = qf('select * from '.$table[0].' where id = '.$oldPhraseId);
			if (!$oldTranslation || strlen($oldTranslation['text']) == 0) continue;

			$newEntityId = getByOldId('\Dictionary\Language', qc('select id from languages where iso = "'.substr($table[0], 2).'"'));
			$language = \Services\Dictionary\LanguageService::get($newEntityId);

			if ($language->getMainEntity() instanceof \Entities\Dictionary\Language && $language->id > 0) {
				$translation = $this->createTranslation($language, $oldTranslation['text']);				
				$translation->translated = fromStamp($oldTranslation['updated']);
				$translation->save();
				$phrase->addTranslation($translation);

			} else {
				debug('nejde to '.$table[0]);return;
			}
		}

		$phrase->save();
		return $phrase;
	}

	private function createPhraseFromString($entityName, $entityAttribute, $requiredLanguages, $level, $text, $textLanguage) {
		$dictionaryType = $this->createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level);

		$phrase = \Services\Dictionary\PhraseService::get();
		$phrase->ready = TRUE;
		$phrase->type = $dictionaryType;

		if ($phrase instanceof D\PhraseService) {
			$phrase->addTranslation($this->createTranslation($textLanguage, $text));
		}

		$phrase->save();
		return $phrase;
	}

	private function createPhrasesFromString($entityName, $entityAttribute, $requiredLanguages, $level, $oldTableName, $oldAttribute, $textLanguage) {

		$dictionaryType = $this->createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level);

		$r = q('select * from '.$oldTableName.' order by id');
		while($x = mysql_fetch_array($r)) {
			$phrase = \Services\Dictionary\PhraseService::get();
			$phrase->ready = TRUE;
			$phrase->type = $dictionaryType;

			if ($phrase instanceof D\PhraseService) {
				$phrase->addTranslation($this->createTranslation($textLanguage, $x[$oldAttribute]));
			}
		}
		$phrase->save();
		return TRUE;
	}

	private function createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level) {
		eval('$level = \Entities\Dictionary\Type::TRANSLATION_LEVEL_'.strtoupper($level).';');
		$dictionaryType = D\TypeService::get();
		$dictionaryType->entityName = $entityName;
		$dictionaryType->entityAttribute = $entityAttribute;
		$dictionaryType->requiredLanguages = $requiredLanguages;
		$dictionaryType->translationLevelRequirement = $level;
		$dictionaryType->save();

		return $dictionaryType;
	}

	private function createTranslation(\Services\Dictionary\LanguageService $language, $text) {
		$translation = \Services\Dictionary\TranslationService::get();
		$translation->language = $language;
		$translation->translation = $text;
		$translation->translated = new \Nette\DateTime();
		$translation->save();

		return $translation;
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