<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Services\Log\Change as ChangeLog;

class ImportAutopilot extends BaseImport {

	public function doImport() {
		$this->savedVariables['importedSections']['autopilot'] = 1;

		$type = \Services\Autopilot\TypeService::get();
		$type->name = 'Administrative region at level 2 has no parent.';
		$type->technicalName = '\Location\Location - Level2HasNoParent';
		$type->mission = 'This Level 2 Administrative location does not have a parent (Level 1 administrative region) defined. Please select one or add a new one.';
		$type->durationPaid = 5;
		$type->stackable = NULL;
		$type->timeLimit = 24*60;
		$type->validation = NULL;
		$type->actions = NULL;
		$type->save();
	
		$this->savedVariables['importedSections']['autopilot'] = 2;

	}

}