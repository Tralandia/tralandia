<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Import as I,
	Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class RadoPresenter extends BasePresenter {

	public function actionDefault() {
		ini_set('max_execution_time', 0);
		$redirect = FALSE;
		if (isset($this->params['dropAllTables'])) {
			$import = new I\BaseImport();
			$import->dropAllTables();

			$this->flashMessage('Dropping Done');
			$redirect = TRUE;
		}
		if (isset($this->params['truncateDatabase'])) {
			$import = new I\BaseImport();
			$import->truncateDatabase();
			$this->flashMessage('Truncate Done');
			$redirect = TRUE;
		}
		if (isset($this->params['undoSection'])) {
			$import = new I\BaseImport();
			$import->undoSection($this->params['undoSection']);
			$this->flashMessage('Section UNDONE');
			$redirect = TRUE;
		}
		if (isset($this->params['importSection'])) {
			\Extras\Models\Service::preventFlush();
			$className = 'Extras\Import\Import'.ucfirst($this->params['importSection']);
			$import = new $className();
			$import->doImport();
			$import->saveVariables();
			\Extras\Models\Service::flush(FALSE);
			$this->flashMessage('Importing Done');
			$redirect = TRUE;
		}

		if ($redirect) {
			//$this->redirect('Rado:default');
		}
	}

	public function renderDefault() {
		$import = new I\BaseImport();

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
		$this->template->sections = $temp;
		return;
	}

}