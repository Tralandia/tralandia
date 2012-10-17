<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUsers extends BaseImport {

	public function doImport($subsection = NULL) {

		// $user1 = \Service\User\User::get(1);
		// $user2 = \Service\User\User::get(4);
		// // $user3 = \Service\User\User::get(3);
		// // $user4 = \Service\User\User::get(4);

		// \Service\User\User::merge($user1, $user2);
		// return;

		$this->{$subsection}();
	}

	private function importSuperAdmins() {

		$role = $this->context->userRoleRepository->findOneBySlug('superadmin');

		$admins = array(
			array('toth@tralandia.com', 'radkos'),
			array('durika@tralandia.com', 'davidheslo'),
			array('czibula@tralandia.com', 'kscibiks'),
			array('vaculciak@tralandia.com', 'branoheslo'),
		);

		$en = $this->context->languageRepository->findOneByIso('en');
		foreach ($admins as $key => $value) {
			// Rado
			$user = $this->context->userEntityFactory->create();
			$user->login = $value[0];
			$user->password = $value[1];
			$user->role = $role;

			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($value[0]));
			$user->contacts = $contacts;

			$user->defaultLanguage = $en;
			$this->model->persist($user);
		}

		$this->model->flush();
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
			$user->oldId = $x['id'];
			$user->role = $role;

			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($x['email']));
			$user->contacts = $contacts;
			
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
			$user->oldId = $x['id'];
			$user->role = $role;

			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($x['email']));
			$user->contacts = $contacts;
			
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
			$user->oldId = $x['id'];
			$user->role = $role;
			$user->invoicingName = new \Extras\Types\Name('', '', $x['name']);

			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($x['email']));
			$user->contacts = $contacts;
			
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

		if ($this->developmentMode == TRUE) {
			$r = q('select * from members where country_id = 46');		
		} else {
			$r = q('select * from members');		
		}
		while($x = mysql_fetch_array($r)) {
			$user = \Service\User\User::getByLogin($x['email']);

			if ($user instanceof \Service\User\User && $user->id > 0) {
				continue;
			}

			$user = \Service\User\User::get();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];
			$user->isOwner = TRUE; //@todo toto tu je len temporary parameter pre import, potom zrusit

			$user->role = $role;

			$user->invoicingSalutation = '';
			$user->invoicingName = new \Extras\Types\Name($x['client_name']);

			if($x['client_email']) $user->invoicingEmail = new \Extras\Types\Email($x['client_email']);
			if($x['client_phone']) $user->invoicingPhone = new \Extras\Types\Phone($x['client_phone']);
			if($x['client_url']) $user->invoicingUrl = new \Extras\Types\Url($x['client_url']);

			$user->invoicingAddress = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['client_address'], $x['client_address2'])),
				'postcode' => $x['client_postcode'],
				'locality' => $x['client_locality'],
				'country' => \Service\Location\Location::getByOldIdAndType($x['client_country_id'], $locationTypeCountry),
			));

			$user->invoicingCompanyName = $x['client_company_name'];
			$user->invoicingCompanyId = $x['client_company_id'];
			$user->invoicingCompanyVatId = $x['client_company_vat_id'];


			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($x['email']));
			$contacts->add(new \Extras\Types\Phone($x['phone']));
			$user->contacts = $contacts;
			
			$user->defaultLanguage = \Service\Dictionary\Language::getByOldId($x['language_id']);
			$user->location = \Service\Location\Location::getByOldIdAndType($x['country_id'], $locationTypeCountry);

			$user->save();
		}
	}

	private function importPotentialOwners() {

		return true; //@todo - toto treba opravit este nefunguje

		$role = \Service\User\Role::getBySlug('potentialowner');
		$locationTypeCountry = \Service\Location\Type::getBySlug('country');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from contacts where country_id = 46 limit 10000');	
		} else {
			$r = q('select * from contacts');
		}

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
			//if (!$user->oldId) $user->oldId = $x['id'];

			$user->role = $role;

			if (!$user->invoicingSalutation) $user->invoicingSalutation = $x['contact_salutation'];
			if (!$user->invoicingName) $user->invoicingName = new \Extras\Types\Name($x['contact_firstname'], '', $x['contact_lastname']);

			if (!$user->invoicingAddress) $user->invoicingAddress = new \Extras\Types\Address(array(
				'address' => array_filter(array($x['address1'], $x['address2'])),
				'postcode' => $x['postcode'],
				'locality' => $x['locality'],
				'country' => \Service\Location\Location::getByOldIdAndType($x['country_id'], $locationTypeCountry),
			));

			$user->subscribed = !(bool)$x['unsubscribed'];
			$user->banned = (bool)$x['banned'];
			$user->full = (bool)$x['full'];
			$user->spam = (bool)$x['spam'];

			$contacts = new \Extras\Types\Contacts();

			$contacts->add(new \Extras\Types\Url($x['url']));

			foreach ($contacts as $key => $value) {
				if(Validators::isEmail($x['email'])) {
					$contacts->add(new \Extras\Types\Email($value));
				} else {
					$contacts->add(new \Extras\Types\Phone($value));
				}
			}
			
			$attraction->conctacts = $contacts;

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
}