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
			$role = \Service\User\Role::get();
			$role->name = $value;
			$role->slug = $role->name;
			$role->save();
		}
		$this->savedVariables['importedSections']['userRoles'] = 1;

	}

}