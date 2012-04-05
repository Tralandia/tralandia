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

		//$this->importSuperAdmins();
		//$this->importAdmins();
		//$this->importManagers();
		//$this->importTranslators();
		$this->importOwners();
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

	private function importAdmins() {

		$role = \Service\User\Role::getBySlug('admin');

		$r = q('select * from members_admins');
		while($x = mysql_fetch_array($r)) {
			$user = \Service\User\User::getByLogin($x['email']);

			if ($user instanceof \Service\User\User && $user->id > 0) {
				continue;
			}

			$user = \Service\User\User::get();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->addRole($role);

			$user->addContact($this->createContact('email', $x['email']));
			
			$user->defaultLanguage = $this->languagesByIso['en'];
			$user->save();
		}

	}

	private function importManagers() {

		$role = \Service\User\Role::getBySlug('manager');

		$r = q('select * from members_managers');
		while($x = mysql_fetch_array($r)) {
			$user = \Service\User\User::getByLogin($x['email']);

			if ($user instanceof \Service\User\User && $user->id > 0) {
				continue;
			}

			$user = \Service\User\User::get();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->addRole($role);

			$user->addContact($this->createContact('email', $x['email']));
			
			$user->defaultLanguage = $this->languagesByIso['en'];

			$assignedCountries = array_unique(array_filter(explode(',', $x['countries'])));
			$assignedLanguages = array_unique(array_filter(explode(',', $x['languages'])));

			foreach ($assignedCountries as $key => $value) {
				foreach ($assignedLanguages as $key2 => $value2) {
					$combination = \Service\User\Combination::get();
					$combination->country = \Service\Location\Country::getByOldId($value)->location;
					$combination->language = $this->languagesByOldId[$value2];
					$combination->languageLevel = \Entity\Dictionary\Type::TRANSLATION_LEVEL_ACTIVE;
					$user->addCombination($combination);
				}
			}
			$user->save();
		}
	}

	private function importTranslators() {

		$role = \Service\User\Role::getBySlug('translator');

		$r = q('select * from members_translators');
		while($x = mysql_fetch_array($r)) {
			$user = \Service\User\User::getByLogin($x['email']);

			if ($user instanceof \Service\User\User && $user->id > 0) {
				continue;
			}

			$user = \Service\User\User::get();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->addRole($role);
			$user->invoicingLastName = $x['name'];

			$user->addContact($this->createContact('email', $x['email']));
			
			$user->defaultLanguage = $this->languagesByIso['en'];

			$details = array(
				'sourceLanguage' => $this->languagesByOldId[$x['language_from']],
				'pricePerStandardPage' => $x['price'],
				'pricePerTicketsStandardPage' => $x['tickets_price'],
			);
			$user->details = $details;

			$combination = \Service\User\Combination::get();
			$combination->language = $this->languagesByOldId[$x['language_to']];
			$combination->languageLevel = \Entity\Dictionary\Type::TRANSLATION_LEVEL_NATIVE;
			$user->addCombination($combination);
			$user->save();
		}
	}


	private function import0000() {

		$role = \Service\User\Role::getBySlug('admin');

		$user = \Service\User\User::get();
		$user->login = $x['email'];
		$user->password = $x['password'];
		$user->addRole($role);

		$user->addContact($this->createContact('email', $x['email']));
		
		$user->defaultLanguage = $this->languagesByIso['en'];
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
		$user->save();

	}
}