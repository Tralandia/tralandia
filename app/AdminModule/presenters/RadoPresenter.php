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
		if (isset($this->params['truncateDatabase'])) {
			$import = new \Import();
			$import->truncateDatabase();

			$this->flashMessage('Truncate Done');
			$this->redirect('Rado:default');
		}
		if (isset($this->params['undoSection'])) {
			$import = new \Import();
			$import->undoSection($this->params['undoSection']);
			$this->flashMessage('Section UNDONE');
			$this->redirect('Rado:default');
		}
		if (isset($this->params['importSection'])) {
			$import = new \Import();
			$action = 'import'.ucfirst($this->params['importSection']);
			$import->{$action}();
			$import->saveVariables();
			$this->redirect('Rado:default');
		}
	}

	public function renderDefault() {
		$import = new \Import();


		$sections = $import->getSections();
		$temp = array();
		foreach ($sections as $key => $value) {
			$temp[] = $key;
		}
		$this->template->sections = $temp;
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