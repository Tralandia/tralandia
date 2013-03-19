<?php

namespace ImportModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Nette\Utils\Arrays,
	Extras\Import as I,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

use Nette\Application\UI\Presenter;

class ImportPresenter extends Presenter {
	public $automaticUrls = array(
		'http://www.sk.tra.com/import?importSection=phraseType',
		'http://www.sk.tra.com/import?importSection=languages',
		'http://www.sk.tra.com/import?importSection=htmlPhrases&subsection=importPhrases',
		'http://www.sk.tra.com/import?importSection=htmlPhrases&subsection=importNewPhrases',
		'http://www.sk.tra.com/import?importSection=amenities',
		'http://www.sk.tra.com/import?importSection=currencies',
		'http://www.sk.tra.com/import?importSection=userRoles',
		'http://www.sk.tra.com/import?importSection=domains',
		'http://www.sk.tra.com/import?importSection=locations&subsection=importContinents',
		'http://www.sk.tra.com/import?importSection=locations&subsection=importRegions',
		'http://www.sk.tra.com/import?importSection=locations&subsection=importLocalities',
		'http://www.sk.tra.com/import?importSection=users&subsection=importSuperAdmins',
		'http://www.sk.tra.com/import?importSection=users&subsection=importAdmins',
		'http://www.sk.tra.com/import?importSection=users&subsection=importManagers',
		'http://www.sk.tra.com/import?importSection=users&subsection=importTranslators',
		'http://www.sk.tra.com/import?importSection=users&subsection=importOwners',
		'http://www.sk.tra.com/import?importSection=users&subsection=importVisitors',
		'http://www.sk.tra.com/import?importSection=rentalTypes',
		'http://www.sk.tra.com/import?importSection=rentalInformation',
		'http://www.sk.tra.com/import?importSection=rentals&countryIso=sk',
		'http://www.sk.tra.com/import?importSection=rentals&countryIso=cz',
		'http://www.sk.tra.com/import?importSection=rentals&countryIso=hu',
		'http://www.sk.tra.com/import?importSection=invoice',
		'http://www.sk.tra.com/import?importSection=interactions&subsection=importRentalReservations',
		'http://www.sk.tra.com/import?importSection=interactions&subsection=importRentalToFriend',
		'http://www.sk.tra.com/import?importSection=interactions&subsection=importSiteReviews',
		'http://www.sk.tra.com/import?importSection=email',
		'http://www.sk.tra.com/import?importSection=updateLanguage',
		'http://www.sk.tra.com/import?importSection=updateEmails',
		'http://www.sk.tra.com/import?importSection=faq',
		'http://www.sk.tra.com/import?importSection=page',
		'http://www.sk.tra.com/import?importSection=pathsegments',
	);

	public $session;

	public function startup() {
		parent::startup();
		$this->session = $this->context->session->getSection('importSession');
	}

	public function actionLocale() {
		phpinfo(); exit;
		$locale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$this->template->output = $locale;	
	}

	public function actionAddPhrases() {
		$entities = \Service\Location\LocationList::getAll(); //@todo - toto sa este vobec pouziva?
		foreach ($entities as $key => $entity) {
			$service = \Service\Location\Location::get($entity);
			$service->save();
		}
	}

	public function actionHelper() {
		if (isset($this->params['indexes'])) {
			$return = '';
			$indexes = array();
			$temp = explode(',', $this->params['indexes']);
			foreach ($temp as $key => $value) {
				$value = trim($value);
				if (strlen($value) == 0) continue;
				$indexes[] = '@ORM\index(name="'.$value.'", columns={"'.$value.'"})';
			}

			$return .= ', indexes={'.implode(', ', $indexes).'}';

			$this->template->output = $return;
		}
	}

	public function renderHelper() {

	}

	public function actionDefault() {
		ini_set('max_execution_time', 0);
		Debugger::$maxDepth = 3;

		$redirect = FALSE;

		if (isset($this->params['autoStart'])) {
			$this->session->automaticNextKey = 0;
			$this->session->automaticOn = 1;
			//d($this->automaticUrls[0]); exit;
			$this->redirectUrl($this->automaticUrls[0]);
		}

		if (isset($this->params['autoStop'])) {
			$this->session->automaticNextKey = 0;
			$this->session->automaticOn = 0;
			$this->redirect('Import:default');
		}


		if (isset($this->params['toggleDevelopmentMode'])) {
			$this->session->developmentMode = !$this->session->developmentMode;
			$this->flashMessage('Development Mode Toggled');
			$redirect = TRUE;
		}

		if (isset($this->params['dropAllTables'])) {
			$import = new I\BaseImport($this->context, $this);
			$import->developmentMode = (bool)$this->session->developmentMode;
			$import->dropAllTables();

			$this->flashMessage('Dropping Done');
			$redirect = TRUE;
		}
		if (isset($this->params['undoSection'])) {
			$import = new I\BaseImport($this->context, $this);
			$import->developmentMode = (bool)$this->session->developmentMode;
			$import->undoSection($this->params['undoSection']);
			$this->flashMessage('Section UNDONE');
			$redirect = TRUE;
		}

		if (isset($this->params['removeNewTablesFromOldDb'])) {
			q('SET FOREIGN_KEY_CHECKS = 0;');
			$tables = q('show tables');
			while ($x = mysql_fetch_array($tables)) {
				$cols = q('show columns from '.$x[0].' like "oldId"');
				$cols = mysql_fetch_array($cols);
				if ($cols) {
					debug($x[0]);
					q('drop table '.$x[0]);
				}
			}
			q('SET FOREIGN_KEY_CHECKS = 1;');
		}

		if (isset($this->params['importSection'])) {
			$section = $this->params['importSection'];
			$className = 'Extras\Import\Import'.ucfirst($section);
			$import = new $className($this->context, $this);
			if(!$import->savedVariables['importedSections'][$section] || !Arrays::get($import->sections, array($section, 'saveImportStatus'), TRUE)) {
				$import->developmentMode = (bool)$this->session->developmentMode;

			
				if (isset($this->params['subsection'])) {
					$subsection = $this->params['subsection'];
					$import->setSubsections($section);
					$import->savedVariables['importedSubSections'][$section][$subsection] = 1;
					if (end($import->sections[$section]['subsections']) == $subsection) {
						$import->savedVariables['importedSections'][$section] = 1;		
					}
					$import->saveVariables();
					$import->doImport($subsection);
				} else {
					//$import->undoSection($section);

					$import->savedVariables['importedSections'][$section] = 1;
					//d($import->savedVariables['importedSections']);
					$import->saveVariables();
					$import->doImport();
				}
				$import->saveVariables();
				
				$this->flashMessage('Import "'.$section.'" prebehol spravne!', 'success');				
			} else {
				$this->flashMessage('Import "'.$section.'" uz bol spreaveny!');
			}
			$redirect = TRUE;
		}

		if (isset($this->params['getPhraseMacro'])) {
			$import = new \Extras\Import\ImportHtmlPhrases;
			$newPhrase = \Service\Dictionary\Phrase::getByOldId($this->params['getPhraseMacro']);
			$newTranslation = \Service\Dictionary\Translation::getByPhraseAndLanguage($newPhrase, \Service\Dictionary\Language::getByIso('en'));
			$this->flashMessage('{_'.$newPhrase->id.', \''.$this->params['getPhraseMacro'].' '.$newTranslation->translation.'\'}');
			$redirect = TRUE;
		}
		//$this->sendJson(array());
		
		if (isset($this->session->automaticOn) && $this->session->automaticOn == 1) {
			if (isset($this->automaticUrls[$this->session->automaticNextKey+1])) {
				$this->session->automaticNextKey++;
				$script = 'Next step: '.$this->automaticUrls[$this->session->automaticNextKey].'<script>document.location.href="'.$this->automaticUrls[$this->session->automaticNextKey].'"</script>';
				$this->sendResponse(new \Nette\Application\Responses\TextResponse($script));
			} else {
				$this->session->automaticOn = 0;
				d('Full import finished.');
				$this->redirect('Import:default');
			}
		}
		if ($redirect) {
			$this->redirect('Import:default');
		}
	}

	public function renderDefault() {
		// $this->template->sections = '';
		// $t = \Services\Location\LocationService::get(848); $t->delete(); return;

		$import = new I\BaseImport($this->context, $this);
		$import->developmentMode = (bool)$this->session->developmentMode;

		$this->template->sections = $import->createNavigation();
		$this->template->developmentMode = $import->developmentMode == TRUE ? "TRUE" : "FALSE";
	}

}
