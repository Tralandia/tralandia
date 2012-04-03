<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Services\Log\Change as ChangeLog;

class ImportUsers extends BaseImport {

	public function doImport() {
		$this->savedVariables['importedSections']['users'] = 1;

		$this->importSuperAdmins();
		$this->importAdmins();
		$this->importRegionalManagers();
		$this->importAssistantsVendors();
		$this->importOwners();
		$this->importVisitors();

		$this->savedVariables['importedSections']['users'] = 2;
	}

	private function importSuperAdmins() {

		$role = \Services\User\Role::getBySlug('superadmin');

		$user = \Services\User\User::get();
		$user->login = 'toth@tralandia.com';
		$user->password = 'radkos';
		$user->addRole($role);

		$user->addContact = '';
		
		$user->defaultLanguage = '';
		$user->addLocation();
		$user->addRentalType();

		$user->invoicingSalutation = '';
		$user->invoicingFirstName = '';
		$user->invoicingLastName = '';
		$user->invoicingEmail = '';
		$user->invoicingPhone = '';
		$user->invoicingUrl = '';
		$user->invoicingAddress = '';
		$user->invoicingCompanyId = '';
		$user->invoicingCompanyVatId = '';

		$user->currentTelmarkOperator = '';
		$user->attributes = '';

		$user->addCombination();

	}

	private function importVisitors() {

		$role = \Services\User\Role::getBySlug('visitor');

		$user = \Services\User\User::get();
		$user->login = '';
		$user->password = '';
		$user->addRole($role);

		$user->addContact = '';
		
		$user->defaultLanguage = '';
		$user->addLocation();
		$user->addRentalType();

		$user->invoicingSalutation = '';
		$user->invoicingFirstName = '';
		$user->invoicingLastName = '';
		$user->invoicingEmail = '';
		$user->invoicingPhone = '';
		$user->invoicingUrl = '';
		$user->invoicingAddress = '';
		$user->invoicingCompanyId = '';
		$user->invoicingCompanyVatId = '';

		$user->currentTelmarkOperator = '';
		$user->attributes = '';

		$user->addCombination();

	}
}