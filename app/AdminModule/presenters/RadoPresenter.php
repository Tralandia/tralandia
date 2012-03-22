<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Import\Import,
	Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class RadoPresenter extends BasePresenter {

	public function actionDefault() {
		ini_set('max_execution_time', 0);
		if (isset($this->params['truncateDatabase'])) {
			$import = new Import();
			$import->truncateDatabase();

			$this->flashMessage('Truncate Done');
			//$this->redirect('Rado:default');
		}
		if (isset($this->params['undoSection'])) {
			$import = new Import();
			$import->undoSection($this->params['undoSection']);
			$this->flashMessage('Section UNDONE');
			//$this->redirect('Rado:default');
		}
		if (isset($this->params['importSection'])) {
			$import = new Import();
			$action = 'import'.ucfirst($this->params['importSection']);
			$import->{$action}();
			$import->saveVariables();
			//$this->redirect('Rado:default');
		}
	}

	public function renderDefault() {
		debug(Debugger::timer());
		$import = new Import();

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
		
		$import = new Import();
		$import->importCompanies();
		//$import->truncateDatabase();
		//$import->importLanguages();
		//$import->importCurrencies();
		//$import->importDomains();
	}

}