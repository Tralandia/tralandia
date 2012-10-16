<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUserRoles extends BaseImport {

	public function doImport($subsection = NULL) {

		$allRoles = array('Guest', 'PotentialOwner', 'Owner', 'Translator', 'Assistant', 'TelmarkManager', 'TelmarkOperator', 'Manager', 'Admin', 'SuperAdmin');

		foreach ($allRoles as $key => $value) {
			$role = $this->context->userRoleEntityFactory->create();
			$role->name = $value;
			$role->slug = $role->name;
			$this->model->persist($role);
		}
		$this->model->flush();
		$this->savedVariables['importedSections']['userRoles'] = 1;

	}

}