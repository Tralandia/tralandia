<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class RadoPresenter extends BasePresenter {

	public function actionDefault() {
		ini_set('max_execution_time', 0);
		$import = new \Import();
		$redirect = FALSE;
		if (isset($this->params['dropAllTables'])) {
			$import->dropAllTables();

			$this->flashMessage('Dropping Done');
			$redirect = TRUE;
		}
		if (isset($this->params['truncateDatabase'])) {
			$import->truncateDatabase();
			$this->flashMessage('Truncate Done');
			$redirect = TRUE;
		}
		if (isset($this->params['undoSection'])) {
			$import->undoSection($this->params['undoSection']);
			$this->flashMessage('Section UNDONE');
			$redirect = TRUE;
		}
		if (isset($this->params['importSection'])) {
			$action = 'import'.ucfirst($this->params['importSection']);
			$import->{$action}();
			$import->saveVariables();
			$this->flashMessage('Importing Done');
			$redirect = TRUE;
		}

		if ($redirect) {
			//$this->redirect('Rado:default');
		}
	}

	public function renderDefault() {
		Debugger::timer();
		$import = new \Import();

		$sections = $import->getSections();
		$temp = array();
		$nextNoImport = FALSE;
		foreach ($sections as $key => $value) {
			if ($nextNoImport == TRUE) {
				$temp[$key] = NULL;
			} else {
				$temp[$key] = (int)@$import->savedVariables['importedSections'][$key];
			}
			if ($temp[$key] == FALSE) {
				$nextNoImport = TRUE;
			}
		}
		debug($temp);
		$this->template->sections = $temp;
		debug(Debugger::timer());
		return;

		// debug($this->params); return;	
		// debug($this->context->session); return;	
		// debug($this->post); return;	
		
		$import = new \Import();
		$import->importCompanies();
		//$import->truncateDatabase();
		//$import->importLanguages();
		//$import->importCurrencies();
		//$import->importDomains();
	}

}