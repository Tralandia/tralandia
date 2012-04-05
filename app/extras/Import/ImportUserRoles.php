<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Services\Log\Change as ChangeLog;

class ImportUserRoles extends BaseImport {

	public function doImport() {
		$this->savedVariables['importedSections']['userRoles'] = 1;

		$allRoles = array('Guest', 'Visitor', 'Owner', 'Translator', 'Assistant', 'Vendor', 'Manager', 'Admin', 'SuperAdmin');

		foreach ($allRoles as $key => $value) {
			$role = \Service\User\Role::get();
			$role->name = $value;
			$role->slug = $role->name;
			$role->save();
		}
		$this->savedVariables['importedSections']['userRoles'] = 2;

	}

}