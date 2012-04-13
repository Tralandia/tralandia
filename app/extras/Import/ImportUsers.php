<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log\Change as ChangeLog;

class ImportUsers extends BaseImport {

	public function doImport($subsection = NULL) {

		$user1 = \Service\User\User::get(1);
		$user2 = \Service\User\User::get(2);
		$user3 = \Service\User\User::get(3);
		$user4 = \Service\User\User::get(4);

		\Service\User\User::merge($user1, $user2);
		return;

		$this->setSubsections('users');

		$this->$subsection();

		$this->savedVariables['importedSubSections']['users'][$subsection] = 1;

		if (end($this->sections['users']['subsections']) == $subsection) {
			$this->savedVariables['importedSections']['users'] = 1;		
		}

	}

	private function importSuperAdmins() {

		$role = \Service\User\Role::getBySlug('superadmin');

		// Rado
		$user = \Service\User\User::get();
		$user->login = 'toth@tralandia.com';
		$user->password = 'radkos';
		$user->addRole($role);
		$user->addContact($this->createContact('email', 'toth@tralandia.com'));
		$user->defaultLanguage = \Service\Dictionary\Language::getByIso('en');
		$user->save();

		// David
		$user = \Service\User\User::get();
		$user->login = 'durika@tralandia.com';
		$user->password = 'davidheslo';
		$user->addRole($role);
		$user->addContact($this->createContact('email', 'durika@tralandia.com'));
		$user->defaultLanguage = \Service\Dictionary\Language::getByIso('en');
		$user->save();

		// Cibi
		$user = \Service\User\User::get();
		$user->login = 'czibula@tralandia.com';
		$user->password = 'kscibiks';
		$user->addRole($role);
		$user->addContact($this->createContact('email', 'czibula@tralandia.com'));
		$user->defaultLanguage = \Service\Dictionary\Language::getByIso('en');
		$user->save();

		// Brano
		$user = \Service\User\User::get();
		$user->login = 'vaculciak@tralandia.com';
		$user->password = 'branoheslo';
		$user->addRole($role);
		$user->addContact($this->createContact('email', 'vaculciak@tralandia.com'));
		$user->defaultLanguage = \Service\Dictionary\Language::getByIso('en');
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
			
			$user->defaultLanguage = \Service\Dictionary\Language::getByIso('en');
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
			
			$user->defaultLanguage = \Service\Dictionary\Language::getByIso('en');

			$assignedCountries = array_unique(array_filter(explode(',', $x['countries'])));
			$assignedLanguages = array_unique(array_filter(explode(',', $x['languages'])));

			foreach ($assignedCountries as $key => $value) {
				foreach ($assignedLanguages as $key2 => $value2) {
					$combination = \Service\User\Combination::get();
					$combination->country = \Service\Location\Country::getByOldId($value)->location;
					$combination->language = \Service\Dictionary\Language::getByOldId($value2);
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
			
			$user->defaultLanguage = \Service\Dictionary\Language::getByIso('en');

			$details = array(
				'sourceLanguage' => \Service\Dictionary\Language::getByOldId($x['language_from']),
				'pricePerStandardPage' => $x['price'],
				'pricePerTicketsStandardPage' => $x['tickets_price'],
			);
			$user->details = $details;

			$combination = \Service\User\Combination::get();
			$combination->language = \Service\Dictionary\Language::getByOldId($x['language_to']);
			$combination->languageLevel = \Entity\Dictionary\Type::TRANSLATION_LEVEL_NATIVE;
			$user->addCombination($combination);
			$user->save();
		}
	}

	private function importOwners() {

		$role = \Service\User\Role::getBySlug('owner');
		$locationTypeCountry = \Service\Location\Type::getBySlug('country');

		$r = q('select * from members where country_id = 46');
		while($x = mysql_fetch_array($r)) {
			$user = \Service\User\User::getByLogin($x['email']);

			if ($user instanceof \Service\User\User && $user->id > 0) {
				continue;
			}

			$user = \Service\User\User::get();

			$user->login = $x['email'];
			$user->password = $x['password'];

			$user->addRole($role);

			$user->invoicingSalutation = '';
			$user->invoicingFirstName = $x['client_name'];
			$user->invoicingLastName = '';
			if($x['client_email']) $user->invoicingEmail = $this->createContact('email', $x['client_email']);
			if($x['client_phone']) $user->invoicingPhone = $this->createContact('phone', $x['client_phone']);
			if($x['client_url']) $user->invoicingUrl = $this->createContact('url', $x['client_url']);
			$user->invoicingAddress = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['client_address'], $x['client_address2'])),
				'postcode' => $x['client_postcode'],
				'locality' => $x['client_locality'],
				'country' => \Service\Location\Location::getByOldIdAndType($x['client_country_id'], $locationTypeCountry),
			));

			$user->invoicingCompanyName = $x['client_company_name'];
			$user->invoicingCompanyId = $x['client_company_id'];
			$user->invoicingCompanyVatId = $x['client_company_vat_id'];


			if($x['email']) $user->addContact($this->createContact('email', $x['email']));
			if($x['phone']) $user->addContact($this->createContact('phone', $x['phone']));
			
			$user->defaultLanguage = \Service\Dictionary\Language::getByOldId($x['language_id']);
			$user->addLocation(\Service\Location\Country::getByOldId($x['country_id'])->location);

			$user->save();
		}
	}

	private function importPotentialOwners() {

		$role = \Service\User\Role::getBySlug('potentialowner');
		$locationTypeCountry = \Service\Location\Type::getBySlug('country');

		$r = q('select * from contacts where country_id = 46 limit 10000');
		//$r = q('select * from contacts');

		while($x = mysql_fetch_array($r)) {

			$x['email'] = trim($email); // toto je pre pripad chybnych emailov, aj take su v db

			$user = \Service\User\User::getByLogin($x['email']);

			if ($user instanceof \Service\User\User && $user->id > 0) {
				// nic sa nedeje, ale nemozem dat continue, lebo nizsie importujem emails a phones naviazane na tento kontakt
			} else {
				$user = \Service\User\User::get();

				$user->login = $x['email'];
				$user->password = NULL;

				$user->addRole($role);

				$user->invoicingSalutation = $x['contact_salutation'];
				$user->invoicingFirstName = $x['contact_firstname'];
				$user->invoicingLastName = $x['contact_lastname'];

				$contactParams = array(
					'subscribed' => !(bool)$x['unsubscribed'],
					'banned' => (bool)$x['banned'],
					'full' => (bool)$x['full'],
					'spam' => (bool)$x['spam'],
				);
				if(Validators::isUrl($x['url'])) $user->addContact($this->createContact('url', $x['url'], $contactParams));
				if(Validators::isEmail($x['email'])) $user->addContact($this->createContact('email', $x['email'], $contactParams));

				$user->invoicingAddress = new \Extras\Types\Address(array(
					'address' => array_filter(array($x['address1'], $x['address2'])),
					'postcode' => $x['postcode'],
					'locality' => $x['city'],
					'country' => \Service\Location\Location::getByOldIdAndType($x['country_id'], $locationTypeCountry),
				));

				$user->invoicingCompanyName = $x['contact_company'];
				
				$user->defaultLanguage = \Service\Dictionary\Language::getByOldId($x['language_id']);
				$user->addLocation(\Service\Location\Country::getByOldId($x['country_id'])->location);
			}


			$r1 = q('select * from contacts_emails where contact_id = '.$x['id'].' and email != "'.$x['email'].'"');
			while ($x1 = mysql_fetch_array($r1)) {
				if(Validators::isEmail($x1['email'])) {
					$user->addContact($this->createContact('email', $x1['email'], $contactParams));
				}
			}

			debug($user); return;

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
		
		$user->defaultLanguage = \Service\Dictionary\Language::getByIso('en');
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

		$user->addCombination();
		$user->save();

	}
}