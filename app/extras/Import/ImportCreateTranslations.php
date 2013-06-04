<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportCreateTranslations extends BaseImport {

	public function doImport($subsection = NULL) {

		$robot = $this->context->createMissingTranslationsRobot;
		$language = $this->context->languageRepository->findOneByIso($this->presenter->getParameter('languageIso'));
		//$this->terminate();

		if($robot->needToRunFor($language)) {
			$robot->runFor($language);
		}
	}

}