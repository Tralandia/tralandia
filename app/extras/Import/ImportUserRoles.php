<?php

namespace Extras\Import;

use Entity\User\Role;
use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUserRoles extends BaseImport {

	public function doImport($subsection = NULL) {

		$allRoles = array(Role::GUEST, Role::OWNER, Role::TRANSLATOR, Role::SUPERADMIN);

		foreach ($allRoles as $key => $value) {
			$role = $this->context->userRoleEntityFactory->create();
			$role->name = ucfirst($value);
			$role->slug = $value;
			if (in_array($value, array(Role::TRANSLATOR, Role::SUPERADMIN))) {
				$role->homePage = ':Admin:Home:default';
			} else if($value == Role::OWNER) {
				$role->homePage = ':Owner:Rental:firstRental';
			}
			$this->model->persist($role);
		}
		$this->model->flush();
		$this->savedVariables['importedSections']['userRoles'] = 1;
	}
}
