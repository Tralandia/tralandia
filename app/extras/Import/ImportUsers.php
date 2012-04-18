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

		// $user1 = \Service\User\User::get(1);
		// $user2 = \Service\User\User::get(4);
		// // $user3 = \Service\User\User::get(3);
		// // $user4 = \Service\User\User::get(4);

		// \Service\User\User::merge($user1, $user2);
		// return;

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

		$countryLocationType = \Service\Location\Type::getBySlug('country');

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
					$combination->country = \Service\Location\Location::getByOldIdAndType($value, $countryLocationType);
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
			$user->addLocation(\Service\Location\Location::getByOldIdAndType($x['country_id'], $locationTypeCountry));

			$user->save();
		}
	}

	private function importPotentialOwners() {

		return true; //@todo - toto treba opravit este nefunguje

		$role = \Service\User\Role::getBySlug('potentialowner');
		$locationTypeCountry = \Service\Location\Type::getBySlug('country');

		$r = q('select * from contacts where country_id = 46 limit 10000');
		//$r = q('select * from contacts');

		while($x = mysql_fetch_array($r)) {
			$contacts = array();
			$r1 = q('select email from contacts_emails where contact_id = '.$x['id']);
			while($x1 = mysql_fetch_array($r1)) {
				$contacts[] = '"'.$x1['email'].'"';
			}

			$r1 = q('select phone from contacts_phones where contact_id = '.$x['id']);
			while($x1 = mysql_fetch_array($r1)) {
				$contacts[] = '"'.$x1['phone'].'"';
			}

			$existingUsers = array();
			if (count($contacts)) {
				$query = 'select user_id from contact_contact where user_id is not null and value in ('.implode(', ', $contacts).')';
				$r1 = qNew($query);
				while($x1 = mysql_fetch_array($r1)) {
					$existingUsers[] = $x1['user_id'];
				}				
			}

			if (count($existingUsers) == 0) {
				$user = \Service\User\User::get();
			} else if (count($existingUsers) == 1) {
				$user = \Service\User\User::get($existingUsers[0]);
			} else {
				eval('$user = \Service\User\User::merge('.implode(', ', $existingUsers).');');
			}


			if (!$user->login) $user->login = $x['email'];
			if (!$user->password) $user->password = NULL;

			$user->addRole($role);

			if (!$user->invoicingSalutation) $user->invoicingSalutation = $x['contact_salutation'];
			if (!$user->invoicingFirstName) $user->invoicingFirstName = $x['contact_firstname'];
			if (!$user->invoicingLastName) $user->invoicingLastName = $x['contact_lastname'];

			if (!$user->invoicingAddress) $user->invoicingAddress = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['address1'], $x['address2'])),
				'postcode' => $x['postcode'],
				'locality' => $x['locality'],
				'country' => \Service\Location\Location::getByOldIdAndType($x['country_id'], $locationTypeCountry),
			));

			$contactParams = array(
				'subscribed' => !(bool)$x['unsubscribed'],
				'banned' => (bool)$x['banned'],
				'full' => (bool)$x['full'],
				'spam' => (bool)$x['spam'],
			);
			if(Validators::isUrl($x['url'])) $user->addContact($this->createContact('url', $x['url'], $contactParams));

			foreach ($contacts as $key => $value) {
				if(Validators::isEmail($x['email'])) {
					$user->addContact($this->createContact('email', $value, $contactParams));
				} else {
					$user->addContact($this->createContact('phone', $value, $contactParams));
				}
			}

			
			$user->defaultLanguage = \Service\Dictionary\Language::getByOldId($x['language_id']);
			$user->addLocation(\Service\Location\Location::getByOldIdAndType($x['country_id'], $locationTypeCountry));

			$user->currentTelmarkOperator = $x['telmark_operator_id'];

			$details = array(
				'counter' => $x['counter'],
				'done' => $x['done'],
				'done_stamp' => $x['done_stamp'],
				'status' => $x['status'],
				'call_count' => $x['call_count'],
			);
			$user->details = $details;
			
			debug($user); return;

			$user->save();
		}
	}

	private function importVisitors() {

		return true; //@todo - toto treba opravit este nefunguje

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