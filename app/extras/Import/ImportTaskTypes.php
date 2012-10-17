<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportTaskTypes extends BaseImport {

	public function doImport($subsection = NULL) {

		$type = $this->context->taskTypeEntityFactory->create();
		$type->name = 'Administrative region at level 2 has no parent.';
		$type->technicalName = '\Location\Location - Level2HasNoParent';
		$type->mission = 'This Level 2 Administrative location does not have a parent (Level 1 administrative region) defined. Please select one or add a new one.';
		$type->timeLimit = 24*60;
		$type->validation = NULL;
		$type->actions = NULL;

		$this->model->persist($type);
		$this->model->flush();
	
		$this->savedVariables['importedSections']['taskTypes'] = 1;

	}

}