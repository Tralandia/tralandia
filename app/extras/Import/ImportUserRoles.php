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
			$this->model->persist($role);
			if (in_array($value, array(Role::TRANSLATOR, Role::SUPERADMIN))) {
				$this->homePage = ':Admin:Home:default';
			}
		}
		$this->model->flush();
		$this->savedVariables['importedSections']['userRoles'] = 1;
	}
}