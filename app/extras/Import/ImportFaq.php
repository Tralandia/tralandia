<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportFaq extends BaseImport {

	public function doImport($subsection = NULL) {

		// $this->createPhraseType('\Entity\Language', 'name')
		// 	->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
		// 	->setPluralVariationsRequired(0)
		// 	->setGenderRequired(0)
		// 	->setGenderVariationsRequired(0)
		// 	->setLocativesRequired(0)
		// 	->setPositionRequired(0)
		// 	// ->setHelpForTranslator('')
		// 	// ->setMonthlyBudget(0)
		// 	// ->setIldId(1)
		// 	->setCheckingRequired(NULL);

		$this->model->flush();

	}

}