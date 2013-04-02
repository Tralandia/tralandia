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

		$allRoles = array(
			Role::GUEST => NULL,
			Role::LOGGED => [
				Role::OWNER => NULL,
				Role::TRANSLATOR => NULL,
				Role::SUPERADMIN => NULL,
			],
		);

		$this->importRoles($allRoles);

		$this->model->flush();
		$this->savedVariables['importedSections']['userRoles'] = 1;
	}

	public function importRoles($roles, Role $parent = NULL)
	{
		foreach ($roles as $roleName => $children) {
			$role = $this->addRole($roleName, $parent);

			$this->model->persist($role);

			if(is_array($children)) {
				$this->importRoles($children, $role);
			}
		}

	}

	public function addRole($name, Role $parent = NULL)
	{
		$role = $this->context->userRoleEntityFactory->create();

		$role->name = ucfirst($name);
		$role->slug = $name;

		if($parent) {
			$role->parent = $parent;
		}

		if (in_array($name, array(Role::TRANSLATOR, Role::SUPERADMIN))) {
			$role->homePage = ':Admin:Rental:list';
		} else if($name == Role::OWNER) {
			$role->homePage = ':Owner:Rental:firstRental';
		}

		return $role;
	}
}
