<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service as S,
	Service\Log\Change as SLog;

class BaseImport {
	public $sections = array(
		'languages' => array(
			'\Dictionary\Language' => array(),
		),
		'htmlPhrases' => array(
			'\Dictionary\Phrase' => array(),
		),
		'currencies' => array(
			'\Currency' => array(),
		),
		'autopilot' => array(
			'\Autopilot\Type' => array(),
		),
		'userRoles' => array(
			'\User\Role' => array(),
		),
		'domains' => array(
			'\Domain' => array(),
		),
		'contactTypes' => array(
			'\Contact\Type' => array(),
		),
		'locations' => array(
			'\Location\Type' => array(),
			'\Location\Location' => array(),
			'\Location\Country' => array(),
			'\Location\Traveling' => array(),
		),
		'companies' => array(
			'\Company\Company' => array(),
		),
		'users' => array(
			'\User\User' => array(),
			'\User\Combination' => array(),
		),
	);

	public $savedVariables = array();
	public $languagesByIso = array();
	public $languagesByOldId = array();

	public function __construct() {
		$this->loadVariables();
		$langs = qNew('select id, iso, oldId from dictionary_language');
		while($value = mysql_fetch_array($langs)) {
			$this->languagesByIso[$value['iso']] = \Service\Dictionary\Language::get($value['id']);
			$this->languagesByOldId[$value['oldId']] = \Service\Dictionary\Language::get($value['id']);
		}
		return;
	}

	public function truncateAllTables() {
		qNew('SET FOREIGN_KEY_CHECKS = 0;');
		$allTables = qNew('SHOW tables');
		while ($table = mysql_fetch_array($allTables)) {
			qNew('truncate table '.$table[0]);
		}
		qNew('SET FOREIGN_KEY_CHECKS = 1;');
		foreach ($this->savedVariables['importedSections'] as $key => $value) {
			$this->savedVariables['importedSections'][$key] = 0;
		}
		$this->savedVariables['importedSubSections'] = array();
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
		$this->savedVariables['importedSubSections'] = array();
		$this->saveVariables();
	}

	public function undoSection($section) {
		$tempSections = array_reverse($this->sections);
		foreach ($tempSections as $key => $value) {
			$value = array_reverse($value);
			foreach ($value as $key2 => $value2) {
				$tableName = str_replace('\\', '_', $key2);
				$tableName = trim($tableName, '_');
				$tableName = strtolower($tableName);

				$r = qNew('select id from '.$tableName.' order by id DESC');
				while ($x = mysql_fetch_array($r)) {
					$serviceName = '\Services'.$key2.'Service';
					$s = $serviceName::get($x['id']);
					$s->delete();
				}
			}
			$this->savedVariables['importedSections'][$key]=0;
			$this->savedVariables['importedSubSections'][$key] = array();
			if ($key == $section) break;
		}
		\Extras\Models\Service\Extras\Models\Service::flush(FALSE);
		$this->saveVariables();
	}

	protected function createPhrasesByOld($entityName, $entityAttribute, $requiredLanguages, $level, $oldTableName, $oldAttribute) {
		$dictionaryType = $this->createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level);

		$r = q('select * from '.$oldTableName.' order by id');
		while($x = mysql_fetch_array($r)) {
			if ($x[$oldAttribute]) {
				$newEntityId = getByOldId($entityName, $x['id']);
				$phrase = $this->createNewPhrase($dictionaryType, $x[$oldAttribute]);
				if ($phrase instanceof D\PhraseService) {
					eval('$s = \Services'.$entityName.'::get('.$newEntityId.');');
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

	protected function createNewPhrase(\Service\Dictionary\Type $type, $oldPhraseId, $oldLocativePhraseId = NULL) {
		$oldPhraseData = qf('select * from dictionary where id = '.$oldPhraseId);
		if (!$oldPhraseData) return FALSE;

		$phrase = \Service\Dictionary\Phrase::get();
		$phrase->ready = (bool)$oldPhraseData['ready'];
		$phrase->type = $type;
		$phrase->oldId = $oldPhraseId;

		if ($phrase->type->requiredLanguages == 'supportedLanguages') {
			$allLanguages = getSupportedLanguages();
		} else {
			$allLanguages = array(); // @todo - dorobit incomingLanguages, konkretne jazyky alebo "nic"
		}

		foreach ($allLanguages as $key => $value) {
			$language = \Service\Dictionary\Language::get($value);
			$oldTranslation = qf('select * from z_'.$language->iso.' where id = '.$oldPhraseId);
			$params = NULL;
			if ($oldLocativePhraseId > 0) {
				$oldTranslationLocative = qf('select * from z_'.$language->iso.' where id = '.$oldLocativePhraseId);
				$params = array(
					'locative' => $oldTranslationLocative['text'],
				);
			}

			$translation = $this->createTranslation($language, (string)$oldTranslation['text'], $params);				
			$translation->timeTranslated = fromStamp($oldTranslation['updated']);
			$phrase->addTranslation($translation);
		}
		$phrase->save();
		return $phrase;
	}

	protected function createPhraseFromString($entityName, $entityAttribute, $requiredLanguages, $level, $text, $textLanguage) {
		$dictionaryType = $this->createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level);

		$phrase = \Service\Dictionary\Phrase::get();
		$phrase->ready = TRUE;
		$phrase->type = $dictionaryType;

		if ($phrase instanceof D\PhraseService) {
			$phrase->addTranslation($this->createTranslation($textLanguage, $text));
		}

		$phrase->save();
		return $phrase;
	}

	protected function createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level, $params = NULL) {

		$dictionaryType = D\Type::getByEntityNameAndEntityAttribute($entityName, $entityAttribute);
		if ($dictionaryType) {
			debug('iba vraciam premennu '.$dictionaryType->entityName.'->'.$dictionaryType->entityAttribute);
			return $dictionaryType;
		} else {
			eval('$level = \Entity\Dictionary\Type::TRANSLATION_LEVEL_'.strtoupper($level).';');
			$dictionaryType = D\Type::get();
			$dictionaryType->entityName = $entityName;
			$dictionaryType->entityAttribute = $entityAttribute;
			$dictionaryType->requiredLanguages = $requiredLanguages;
			$dictionaryType->translationLevelRequirement = $level;
			if (isset($params) && count($params) > 0) {
				foreach ($params as $key => $value) {
					$dictionaryType->$key = $value;
				}
			}
			$dictionaryType->save();			
			return $dictionaryType;
		}
	}

	protected function createTranslation(\Service\Dictionary\Language $language, $text, $variations = NULL) {
		$translation = \Service\Dictionary\Translation::get();
		$translation->language = $language;
		$translation->translation = $text;
		$translation->timeTranslated = new \Nette\DateTime();

		if ($variations == NULL) {
			$variations = array();
		}
		$variations['translation'] = $text;
		$translation->variations = $variations;

		return $translation;
	}

	protected function createContact($slug, $value, $params = array()) {

		if (!$value || strlen($value) == 0) {
			throw new \Nette\UnexpectedValueException('BaseImport::createContact - no value received');
		}

		$contact = \Service\Contact\Contact::get();
		$contact->type = \Service\Contact\Type::getBySlug($slug);
		$contact->value = $value;

		foreach ($params as $key => $value) {
			$contact->$key = $value;
		}

		return $contact;
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