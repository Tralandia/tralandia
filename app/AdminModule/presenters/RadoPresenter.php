<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Import as I,
	Service\Dictionary as D,
	Service as S,
	Service\Log\Change as SLog;

class RadoPresenter extends BasePresenter {

	public $session;

	public function startup() {
		parent::startup();
		$this->session = $this->context->session->getSection('importSession');
	}

	public function actionDefault() {
		ini_set('max_execution_time', 0);
		Debugger::$maxDepth = 3;

		$redirect = FALSE;
		if (isset($this->params['toggleDevelopmentMode'])) {
			$this->session->developmentMode = !$this->session->developmentMode;
			$this->flashMessage('Development Mode Toggled');
			$redirect = TRUE;
		}

		if (isset($this->params['dropAllTables'])) {
			$import = new I\BaseImport();
			$import->developmentMode = (bool)$this->session->developmentMode;
			$import->dropAllTables();

			$this->flashMessage('Dropping Done');
			$redirect = TRUE;
		}
		if (isset($this->params['truncateAllTables'])) {
			$import = new I\BaseImport();
			$import->developmentMode = (bool)$this->session->developmentMode;
			$import->truncateAllTables();
			$this->flashMessage('Truncating Done');
			$redirect = TRUE;
		}
		if (isset($this->params['undoSection'])) {
			$import = new I\BaseImport();
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
			//\Extras\Models\Service::preventFlush();
			$className = 'Extras\Import\Import'.ucfirst($this->params['importSection']);
			$import = new $className();
			$import->developmentMode = (bool)$this->session->developmentMode;
			if (isset($this->params['subsection'])) {
				$import->doImport($this->params['subsection']);
			} else {
				$import->doImport();
			}
			$import->saveVariables();
			\Extras\Models\Service::flush(FALSE);
			$this->flashMessage('Importing Done');
			$redirect = TRUE;
		}

		if (isset($this->params['getPhraseMacro'])) {
			$import = new \Extras\Import\ImportHtmlPhrases;
			$newPhrase = \Service\Dictionary\Phrase::getByOldId($this->params['getPhraseMacro']);
			$newTranslation = \Service\Dictionary\Translation::getByPhraseAndLanguage($newPhrase, \Service\Dictionary\Language::getByIso('en'));
			$this->flashMessage('{_'.$newPhrase->id.', \''.$this->params['getPhraseMacro'].' '.$newTranslation->translation.'\'}');
			$redirect = TRUE;
		}

		if ($redirect) {
			//$this->redirect('Rado:default');
		}
	}

	public function renderDefault() {
		// $this->template->sections = '';
		// $t = \Services\Location\LocationService::get(848); $t->delete(); return;

		$import = new I\BaseImport();
		$import->developmentMode = (bool)$this->session->developmentMode;

		$this->template->sections = $import->createNavigation();
		$this->template->developmentMode = $import->developmentMode == TRUE ? "TRUE" : "FALSE";
		return;
	}

}