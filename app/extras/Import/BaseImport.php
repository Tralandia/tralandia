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
	Service\Log as SLog;

class BaseImport {
	public $sections = array(
		'languages' => array(
			'entities' => array(
				'\Dictionary\Language' => array(),
			),
			'subsections' => array(),
		),
		'htmlPhrases' => array(
			'entities' => array(
				'\Dictionary\Phrase' => array(),
			),
			'subsections' => array(),
		),
		'currencies' => array(
			'entities' => array(
				'\Currency' => array(),
			),
			'subsections' => array(),
		),
		'autopilot' => array(
			'entities' => array(
				'\Autopilot\Type' => array(),
			),
			'subsections' => array(),
		),
		'userRoles' => array(
			'entities' => array(
				'\User\Role' => array(),
			),
			'subsections' => array(),
		),
		'domains' => array(
			'entities' => array(
				'\Domain' => array(),
			),
			'subsections' => array(),
		),
		'contactTypes' => array(
			'entities' => array(
				'\Contact\Type' => array(),
			),
			'subsections' => array(),
		),
		'locations' => array(
			'entities' => array(
				'\Location\Type' => array(),
				'\Location\Location' => array(),
				'\Location\Traveling' => array(),
			),
			'subsections' => array('importContinents', 'importCountries', 'importTravelings', 'importRegions', 'importAdministrativeRegions1', 'importAdministrativeRegions2', 'importLocalities'),
		),
		'locationsPolygons' => array(
			'entities' => array(
			),
			'subsections' => array(),
		),
		'companies' => array(
			'entities' => array(
				'\Company\Company' => array(),
			),
			'subsections' => array('importCompanies', 'importOffices', 'importBankAccounts'),
		),
		'users' => array(
			'entities' => array(
				'\User\User' => array(),
				'\User\Combination' => array(),
			),
			'subsections' => array('importSuperAdmins', 'importAdmins', 'importManagers', 'importTranslators', 'importOwners', 'importPotentialOwners', 'importVisitors'),
		),
		'amenities' => array(
			'entities' => array(
				'\Rental\AmenityType' => array(),
				'\Rental\Amenity' => array(),
			),
			'subsections' => array(),
		),
		'rentalTypes' => array(
			'entities' => array(
				'\Rental\Type' => array(),
			),
			'subsections' => array(),
		),
		'rentals' => array(
			'entities' => array(
				'\Rental\Rental' => array(),
			),
			'subsections' => array(),
		),
		'invoicingStart' => array(
			'entities' => array(
				'\Invoicing\ServiceDuration' => array(),
				'\Invoicing\ServiceType' => array(),
				'\Invoicing\UseType' => array(),
				'\Invoicing\Package' => array(),
				'\Invoicing\Service' => array(),
				'\Invoicing\Marketing' => array(),
			),
			'subsections' => array(),
		),
		'invoicing' => array(
			'entities' => array(
				'\Invoicing\Invoice' => array(),
				'\Invoicing\Item' => array(),
			),
			'subsections' => array(),
		),
		'attractions' => array(
			'entities' => array(
				'\Attraction\Type' => array(),
				'\Attraction\Attraction' => array(),
			),
			'subsections' => array(),
		),
		'interactions' => array(
			'entities' => array(
				'\User\RentalReservation' => array(),
				'\User\RentalQuestion' => array(),
				'\User\RentalReview' => array(),
				'\User\RentalToFriend' => array(),
				'\User\SiteOwnerReview' => array(),
				'\User\SiteVisitorReview' => array(),
			),
			'subsections' => array('importRentalReservations', 'importRentalQuestions', 'importRentalToFriend', 'importSiteOwnerReviews', 'importSiteVisitorReviews'),
		),
		'emailing' => array(
			'entities' => array(
				'\Emailing\Template' => array(),
			),
			'subsections' => array(),
		),
		'seo' => array(
			'entities' => array(
				'\Seo\TitleSuffix' => array(),
				'\Seo\SeoUrl' => array(),
			),
			'subsections' => array('importSeoUrls'),
		),
		'tickets' => array(
			'entities' => array(
				'\Ticket\Ticket' => array(),
				'\Ticket\Message' => array(),
			),
			'subsections' => array(),
		),
		// 'pathsegments' => array(
		// 	'entities' => array(
		// 		'\Routing\PathSegment' => array(),
		// 	),
		// 	'subsections' => array(),
		// ),
	);

	public $savedVariables = array();
	public $developmentMode = TRUE;

	public function __construct() {
		$this->loadVariables();

		return;
	}

	public function setSubsections($section = NULL) {
		if ($section) {
			if (!isset($this->savedVariables['importedSubSections'])) {
				$this->savedVariables['importedSubSections'] = array();
			}
			if (!isset($this->savedVariables['importedSubSections'][$section])) {
				$this->savedVariables['importedSubSections'][$section] = array();
			}

			if (count($this->savedVariables['importedSubSections'][$section]) == 0) {
				foreach ($this->sections[$section]['subsections'] as $key => $value) {
					$this->savedVariables['importedSubSections'][$section][$value] = 0;
				}
			}
		}
	}

	public function truncateAllTables() {
		qNew('SET FOREIGN_KEY_CHECKS = 0;');
		$allTables = qNew('SHOW tables');
		while ($table = mysql_fetch_array($allTables)) {
			if ($table[0] == '__importVariables') continue;
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
			if ($table[0] == '__importVariables') continue;
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
			$value = array_reverse($value['entities']);
			foreach ($value as $key2 => $value2) {
				$tableName = str_replace('\\', '_', $key2);
				$tableName = trim($tableName, '_');
				$tableName = strtolower($tableName);
				$r = qNew('select id from '.$tableName.' order by id DESC');
				while ($x = mysql_fetch_array($r)) {
					$serviceName = '\Service'.$key2;
					$s = $serviceName::get($x['id']);
					if ($s) $s->delete();
				}
			}
			$this->savedVariables['importedSections'][$key]=0;
			$this->savedVariables['importedSubSections'][$key] = array();
			if ($key == $section) break;
		}
		\Extras\Models\Service::flush(FALSE);
		foreach ($value as $key2 => $value2) {
			$tableName = str_replace('\\', '_', $key2);
			$tableName = trim($tableName, '_');
			$tableName = strtolower($tableName);
			qNew('ALTER TABLE '.$tableName.' AUTO_INCREMENT = 1');
		}
		$this->saveVariables();
	}

	protected function createPhrasesByOld($entityName, $entityAttribute, $requiredLanguages, $level, $oldTableName, $oldAttribute) {
		$dictionaryType = $this->createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level);

		$r = q('select * from '.$oldTableName.' order by id');
		while($x = mysql_fetch_array($r)) {
			if ($x[$oldAttribute]) {
				$newEntityId = getByOldId($entityName, $x['id']);
				$phrase = $this->createNewPhrase($dictionaryType, $x[$oldAttribute]);
				if ($phrase instanceof \Service\Dictionary\Phrase) {
					eval('$s = \Service'.$entityName.'::get('.$newEntityId.');');
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
		if (!$oldPhraseData) {
			debug('Nenasiel som staru Phrase podla starej ID '.$oldPhraseId);
			$oldPhraseData = array(
				'ready' => 1,
			);
			//throw new \Nette\UnexpectedValueException('Nenasiel som staru Phrase podla starej ID '.$oldPhraseId);
		}
		$phrase = \Service\Dictionary\Phrase::get();
		$phrase->ready = (bool)$oldPhraseData['ready'];
		$phrase->type = $type;
		$phrase->oldId = $oldPhraseId;

		if ($phrase->type->requiredLanguages == 'supportedLanguages') {
			$allLanguages = getSupportedLanguages();
			$skipEmptyTranslations = FALSE;
		} else {
			$allLanguages = getAllLanguages();
			$skipEmptyTranslations = TRUE;
		}

		foreach ($allLanguages as $key => $value) {
			$language = \Service\Dictionary\Language::get($value);
			$oldTranslation = qf('select * from z_'.$language->iso.' where id = '.$oldPhraseId);
			if ($skipEmptyTranslations && strlen($oldTranslation['text']) == 0) continue;

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
		//$phrase->save();
		return $phrase;
	}

	protected function createPhraseFromString($entityName, $entityAttribute, $requiredLanguages, $level, $text, $textLanguage) {
		$dictionaryType = $this->createDictionaryType($entityName, $entityAttribute, $requiredLanguages, $level);

		$phrase = \Service\Dictionary\Phrase::get();
		$phrase->ready = TRUE;
		$phrase->type = $dictionaryType;

		if ($phrase instanceof \Service\Dictionary\Phrase) {
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

	public function getSections() {
		return $this->sections;
	}

	public function loadVariables() {
		$t = qNew('select value from __importVariables where id = 1');
		$t = mysql_fetch_array($t);
		$t = $t[0];
		$this->savedVariables = \Nette\Utils\Json::decode($t, TRUE);
		foreach ($this->sections as $key => $value) {
			if (!isset($this->savedVariables['importedSections'][$key])) {
				$this->savedVariables['importedSections'][$key] = array();
			}
		}
	}

	public function saveVariables() {
		qNew('update __importVariables set value ="'.mysql_real_escape_string(\Nette\Utils\Json::encode($this->savedVariables)).'"  where id = 1');
	}

	public function createNavigation() {
		$return = array();
		$nextToImport = TRUE;
		foreach ($this->sections as $key => $value) {
			$return[$key] = array(
				'name' => ucfirst($key),
				'undo' => (bool)((int)$this->savedVariables['importedSections'][$key] > 0),
				'import' => (!$this->savedVariables['importedSections'][$key] && $nextToImport == TRUE),
				'subsections' => array(),
				'rootImport' => !(bool)count($value['subsections']),
			);
			if (count($value['subsections']) && $return[$key]['import']) {
				$nextSubsectionToImport = TRUE;
				foreach ($value['subsections'] as $key2 => $value2) {
					$return[$key]['subsections'][$value2] = array(
						'name' => ucfirst($value2),
						'import' => (!@$this->savedVariables['importedSubSections'][$key][$value2] && $nextSubsectionToImport == TRUE),
					);
					if ($return[$key]['subsections'][$value2]['import']) {
						$nextSubsectionToImport = FALSE;
					}
				}
			}
			if ($return[$key]['import']) {
				$nextToImport = FALSE;
			}
		}
		return $return;
	}
}