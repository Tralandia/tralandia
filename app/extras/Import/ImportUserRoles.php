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

		$allRoles = array('Guest', 'PotentialOwner', 'Owner', 'Translator', 'Admin', 'SuperAdmin');

		foreach ($allRoles as $key => $value) {
			$role = $this->context->userRoleEntityFactory->create();
			$role->name = $value;
			$role->slug = $role->name;
			$this->model->persist($role);
			if (in_array($value, array('Translator', 'Admin', 'SuperAdmin'))) {
				$this->homePage = ':Admin:Home:de­fault';
			}
		}
		$this->model->flush();
		$this->savedVariables['importedSections']['userRoles'] = 1;
	}
}