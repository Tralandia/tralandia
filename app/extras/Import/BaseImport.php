<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service as S,
	Service\Log as SLog;

class BaseImport {
	public $sections = array(
		'phraseType' => array(
			'entities' => array(
			),
			'subsections' => array(),
			'saveImportStatus' => FALSE,
		),
		'languages' => array(
			'entities' => array(
				'\Language' => array(),
			),
			'subsections' => array(),
		),
		'htmlPhrases' => array(
			'entities' => array(
				'\Phrase\Phrase' => array(),
			),
			'subsections' => array(),
		),
		'currencies' => array(
			'entities' => array(
				'\Currency' => array(),
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
		'locations' => array(
			'entities' => array(
				'\Location\Type' => array(),
				'\Location\Location' => array(),
				//'\Location\Traveling' => array(),
			),
			'subsections' => array('importContinents', 'importCountries', /*'importTravelings',*/ 'importRegions', 'importAdministrativeRegions1', 'importAdministrativeRegions2', 'importLocalities'),
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
			'saveImportStatus' => FALSE,
		),
		'invoiceStart' => array(
			'entities' => array(
				'\Invoice\ServiceDuration' => array(),
				'\Invoice\ServiceType' => array(),
				'\Invoice\UseType' => array(),
				'\Invoice\Package' => array(),
				'\Invoice\Service' => array(),
				'\Invoice\Marketing' => array(),
			),
			'subsections' => array(),
		),
		'invoice' => array(
			'entities' => array(
				'\Invoice\Invoice' => array(),
				'\Invoice\Item' => array(),
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
		'email' => array(
			'entities' => array(
				'\Email\Template' => array(),
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
		'pathsegments' => array(
			'entities' => array(
				'\Routing\PathSegment' => array(),
			),
			'subsections' => array(),
		),
		'taskTypes' => array(
			'entities' => array(
				'\Task\Type' => array(),
			),
			'subsections' => array(),
			'saveImportStatus' => FALSE,
		),
		'updateLanguage' => array(
			'entities' => array(
			),
			'subsections' => array(),
			'saveImportStatus' => FALSE,
		),
		'updateEmails' => array(
			'entities' => array(
			),
			'subsections' => array(),
			'saveImportStatus' => FALSE,
		),
	);

	public $savedVariables = array();
	public $developmentMode = TRUE;

	public $context;

	public function __construct($context) {
		$this->context = $context;
		$this->model = $context->model;
		$this->loadVariables();

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
				// $tableName = str_replace('\\', '_', $key2);
				// $tableName = trim($tableName, '_');
				// $tableName = strtolower($tableName);
				// $r = qNew('select id from '.$tableName.' order by id DESC');
				// //debug('select id from '.$tableName.' order by id DESC');
				// while ($x = mysql_fetch_array($r)) {
				// 	$serviceName = $this->getServiceFactoryName($key2);
				// 	$s = $this->context->{$serviceName}->create($x['id']);
				// 	if ($s) $s->delete();
				// }
				$repositoryName = $this->getRepositoryName($key2);
				//d($this->context->{$repositoryName});
				$allRows = $this->context->{$repositoryName}->deleteAll();
			}
			$this->savedVariables['importedSections'][$key]=0;
			$this->savedVariables['importedSubSections'][$key] = array();
			if ($key == $section) break;
		}

		foreach ($value as $key2 => $value2) {
			$tableName = getTableName($key2);
			qNew('ALTER TABLE '.$tableName.' AUTO_INCREMENT = 1');
		}
		$this->saveVariables();
	}

	// protected function createPhrasesByOld($entityName, $entityAttribute, $level, $oldTableName, $oldAttribute) {
	// 	$phraseType = $this->createPhraseType($entityName, $entityAttribute, $level);

	// 	$r = q('select id, '.$oldAttribute.' from '.$oldTableName.' order by id');
	// 	while($x = mysql_fetch_array($r)) {
	// 		if ($x[$oldAttribute]) {
	// 			$newEntityId = getByOldId($entityName, $x['id']);
	// 			$phrase = $this->createNewPhrase($phraseType, $x[$oldAttribute]);
	// 			if ($phrase instanceof \Service\BaseService) {
	// 				$serviceName = $this->getServiceFactoryName($entityName);
	// 				$s = $this->context->{$serviceName}->create($newEntityId);
	// 				if ($s->id > 0) {
	// 					$s->{$entityAttribute} = $phrase;
	// 					$s->save(FALSE);						
	// 				} else {
	// 					debug($s);
	// 					debug($newEntityId); 
	// 					return;	
	// 				}
	// 			}
	// 		} else {
	// 			$phrase = $this->createNewPhrase($phraseType);
	// 		}
	// 	}
	// }

	protected function createNewPhrase(\Entity\BaseEntity $type, $oldPhraseId = NULL, $oldLocativePhraseId = NULL, $locativeKeys = NULL) {

		$oldPhraseData = NULL;
		if ($oldPhraseId) {
			$oldPhraseData = qf('select * from dictionary where id = '.$oldPhraseId);
		}
		if (!$oldPhraseData) {
			debug('Nenasiel som staru Phrase podla stareho ID '.$oldPhraseId);
			$oldPhraseData = array(
				'ready' => 1,
			);
			//throw new \Nette\UnexpectedValueException('Nenasiel som staru Phrase podla starej ID '.$oldPhraseId);
		}
		$phraseService = $this->context->phraseServiceFactory->create();
		$phrase = $phraseService->getEntity();
		$phrase->ready = (bool)$oldPhraseData['ready'];
		$phrase->type = $type;
		$phrase->oldId = $oldPhraseId;

		$allLanguages = getSupportedLanguages();
		foreach ($allLanguages as $key => $value) {
			$language = $this->context->languageRepository->find($value);
			$oldTranslation = qf('select * from z_'.$language->iso.' where id = '.$oldPhraseId);
			if (strlen($oldTranslation['text']) == 0) continue;

			// $variations = NULL;
			// if ($oldLocativePhraseId > 0) {
			// 	$oldTranslationLocative = qf('select * from z_'.$language->iso.' where id = '.$oldLocativePhraseId);
			// 	if (strlen($oldTranslationLocative)) {
			// 		$variations = array();
			// 		$variations[$locativeKeys[0]][$locativeKeys[1]]['locative'] = $oldTranslationLocative['text'];
			// 	}
			// }
			$translation = $phraseService->createTranslation($language, (string)$oldTranslation['text']);
			$translation->timeTranslated = fromStamp($oldTranslation['updated']);
		}
		//$phrase->save();
		return $phrase;
	}

	/**
	 * Vytvory novu Phrase entitu
	 * @param  text $entityName
	 * @param  text $entityAttribute
	 * @param  text $level
	 * @param  text $text
	 * @param  \Entity\Language $textLanguage | string $textLanguage
	 * @return \Entity\Phrase\Phrase
	 */
	protected function createPhraseFromString($entityName, $entityAttribute, $level, $text, $textLanguage) {
		$phraseType = $this->createPhraseType($entityName, $entityAttribute, $level);

		$phraseService = $this->context->phraseServiceFactory->create();
		$phrase = $phraseService->getEntity();
		$phrase->ready = TRUE;
		$phrase->type = $phraseType;

		if(is_string($textLanguage)) {
			$textLanguage = $this->context->languageRepository->findOneBy(array('iso' => $textLanguage));
		}
		$translation = $phraseService->createTranslation($textLanguage, $text);

		return $phrase;
	}


	/**
	 * Vytvory novy typ frazy ak neexistuje, inak vracia existujuci
	 * @param  string $entityName
	 * @param  string $entityAttribute
	 * @param  string $level
	 * @param  [type] $params
	 * @return \Entity\Phrase\Type
	 */
	protected function createPhraseType($entityName, $entityAttribute, $level = NULL, $params = NULL) {
		if (substr($entityName, 0, 7) != '\Entity' && $entityName != 'Html') {
			$entityName = '\Entity'.$entityName;
		}

		$phraseTypeRepository = $this->context->phraseTypeRepository;
		$phraseType = $phraseTypeRepository->findOneBy(array('entityName' => $entityName, 'entityAttribute' => $entityAttribute));

		if ($phraseType) {
			//debug('iba vraciam premennu '.$phraseType->entityName.'->'.$phraseType->entityAttribute);
			return $phraseType;
		} else {
			$phraseType = $this->context->phraseTypeEntityFactory->create();
			$phraseType->entityName = $entityName;
			$phraseType->entityAttribute = $entityAttribute;
			if (isset($params) && count($params) > 0) {
				foreach ($params as $key => $value) {
					$phraseType->$key = $value;
				}
			}
			$this->context->model->persist($phraseType);	
			return $phraseType;
		}
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
			if(array_key_exists('saveImportStatus', $value)) {
				$saveImportStatus = $value['saveImportStatus'];
			} else {
				$saveImportStatus = TRUE;
			}
			$return[$key] = array(
				'name' => ucfirst($key),
				'undo' => ($saveImportStatus ? (bool)((int)$this->savedVariables['importedSections'][$key] > 0) : TRUE),
				'import' => ($saveImportStatus ? (!$this->savedVariables['importedSections'][$key] && $nextToImport == TRUE) : TRUE),
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
			if (!$this->savedVariables['importedSections'][$key]) {
				$nextToImport = FALSE;
			}
		}
		return $return;
	}

	public function getServiceFactoryName($namespace) {
		$serviceName = array_filter(array_unique(explode('\\', $namespace)));
		$serviceName = lcfirst(implode('', $serviceName)) . 'ServiceFactory';
		return $serviceName;
	}

	public function getRepositoryName($namespace) {
		$serviceName = $this->getServiceFactoryName($namespace);
		$repositoryName = str_replace('ServiceFactory', '', $serviceName);
		return $repositoryName . 'Repository';
	}
}