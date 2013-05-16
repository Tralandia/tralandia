<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUpdateTranslationStatus extends BaseImport {

	public function doImport($subsection = NULL) {

		$robot = $this->context->updateTranslationStatusRobot;
		$robot->setCurrentIteration($this->presenter->getParameter('iteration'));

		//$this->terminate();

		if($robot->needToRun()) {
			$robot->run();
		}
	}

}