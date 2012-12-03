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

		$technicalName = '\Location\Location - Level2HasNoParent';
		$type = $this->context->taskTypeRepositoryAccessor->get()->findOneByTechnicalName($technicalName);
		if(!$type) {
			$type = $this->context->taskTypeEntityFactory->create();
			$this->model->persist($type);
		}
		$type->name = 'Administrative region at level 2 has no parent.';
		$type->technicalName = $technicalName;
		$type->mission = 'This Level 2 Administrative location does not have a parent (Level 1 administrative region) defined. Please select one or add a new one.';
		$type->timeLimit = 24*60;
		$type->validation = NULL;
		$type->actions = NULL;

		$this->model->persist($type);

		//---
		$technicalName = '\Phrase\Translation - Translation Required';
		$type = $this->context->taskTypeRepositoryAccessor->get()->findOneByTechnicalName($technicalName);
		if(!$type) {
			$type = $this->context->taskTypeEntityFactory->create();
			$this->model->persist($type);
		}
		$type->name = 'Translation Required';
		$type->technicalName = $technicalName;
		$type->mission = 'Preloz toto!';
		$type->timeLimit = 24*60;
		$type->validation = NULL;
		$type->actions = NULL;



		$this->model->flush();

	}

}