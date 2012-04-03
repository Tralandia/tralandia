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
		//$this->importAdmins();
		//$this->importRegionalManagers();
		//$this->importAssistantsVendors();
		//$this->importOwners();
		//$this->importVisitors();

		$this->savedVariables['importedSections']['users'] = 2;
	}

	private function importSuperAdmins() {

		$role = \Service\User\Role::getBySlug('superadmin');

		// Rado
		$user = \Service\User\User::get();
		$user->login = 'toth@tralandia.com';
		$user->password = 'radkos';
		$user->addRole($role);
		$user->addContact($this->createContact('email', 'toth@tralandia.com'));
		$user->defaultLanguage = $this->languagesByIso['en'];
		$user->save();

		// David
		$user = \Service\User\User::get();
		$user->login = 'durika@tralandia.com';
		$user->password = 'davidheslo';
		$user->addRole($role);
		$user->addContact($this->createContact('email', 'durika@tralandia.com'));
		$user->defaultLanguage = $this->languagesByIso['en'];
		$user->save();

		// Cibi
		$user = \Service\User\User::get();
		$user->login = 'czibula@tralandia.com';
		$user->password = 'kscibiks';
		$user->addRole($role);
		$user->addContact($this->createContact('email', 'czibula@tralandia.com'));
		$user->defaultLanguage = $this->languagesByIso['en'];
		$user->save();

		// Brano
		$user = \Service\User\User::get();
		$user->login = 'vaculciak@tralandia.com';
		$user->password = 'branoheslo';
		$user->addRole($role);
		$user->addContact($this->createContact('email', 'vaculciak@tralandia.com'));
		$user->defaultLanguage = $this->languagesByIso['en'];
		$user->save();

		return TRUE;
	}

	private function importVisitors() {

		$role = \Service\User\Role::getBySlug('visitor');

		$user = \Service\User\User::get();
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