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

	// @todo spravit invoicingData import

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

			$user->language = $en;
			$this->model->persist($user);
		}

		$this->model->flush();
		return TRUE;
	}

	private function importAdmins() {

		$role = $this->context->userRoleRepository->findOneBySlug('admin');

		$r = q('select * from members_admins');
		while($x = mysql_fetch_array($r)) {
			$user = $this->context->userRepository->findOneByLogin($x['email']);

			if ($user) {
				continue;
			}

			$user = $this->context->userEntityFactory->create();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];
			$user->role = $role;

			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($x['email']));
			$user->contacts = $contacts;
			
			$user->language = $this->context->languageRepository->findOneByIso('en');
			$this->model->persist($user);
		}
		$this->model->flush();

	}

	private function importManagers() {

		$role = $this->context->userRoleRepository->findOneBySlug('manager');

		$countryLocationType = $this->context->locationTypeRepository->findOneBySlug('country');

		$r = q('select * from members_managers');
		while($x = mysql_fetch_array($r)) {
			$user = $this->context->userRepository->findOneByLogin($x['email']);

			if ($user) {
				continue;
			}

			$user = $this->context->userEntityFactory->create();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];
			$user->role = $role;

			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($x['email']));
			$user->contacts = $contacts;
			
			$user->language = $this->context->languageRepository->findOneByIso('en');

			$assignedCountries = array_unique(array_filter(explode(',', $x['countries'])));
			$assignedLanguages = array_unique(array_filter(explode(',', $x['languages'])));

			foreach ($assignedCountries as $key => $value) {
				foreach ($assignedLanguages as $key2 => $value2) {
					$combination = $this->context->userCombinationEntityFactory->create();

					$combination->country = $this->context->locationRepository->findOneBy(array('oldId'=>$value, 'type'=>$countryLocationType));
					$combination->language = $this->context->languageRepository->findOneByOldId($value2);
					$user->addCombination($combination);
				}
			}
			$this->model->persist($user);
		}
		$this->model->flush();
	}

	private function importTranslators() {

		$role = $this->context->userRoleRepository->findOneBySlug('translator');

		$r = q('select * from members_translators');
		while($x = mysql_fetch_array($r)) {
			$user = $this->context->userRepository->findOneByLogin($x['email']);

			if ($user) {
				continue;
			}

			$user = $this->context->userEntityFactory->create();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];
			$user->role = $role;
			// $user->invoicingData = @todo;

			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($x['email']));
			$user->contacts = $contacts;
			
			$user->language = $this->context->languageRepository->findOneByIso('en');

			$details = array(
				'sourceLanguage' => $this->context->languageRepository->findOneByOldId($x['language_from']),
				'pricePerStandardPage' => $x['price'],
				'pricePerTicketsStandardPage' => $x['tickets_price'],
			);
			$user->details = $details;

			$combination = $this->context->userCombinationEntityFactory->create();
			$combination->language = $this->context->languageRepository->findOneByOldId($x['language_to']);
			$user->addCombination($combination);
			$this->model->persist($user);
		}
		$this->model->flush();
	}

	private function importOwners() {

		$role = $this->context->userRoleRepository->findOneBySlug('owner');
		$locationTypeCountry = $this->context->locationTypeRepository->findOneBySlug('country');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from members where country_id = 46');		
		} else {
			$r = q('select * from members');		
		}
		while($x = mysql_fetch_array($r)) {
			$user = $this->context->userRepository->findOneByLogin($x['email']);

			if ($user) {
				continue;
			}

			$user = $this->context->userEntityFactory->create();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];

			$user->role = $role;

			// $user->invoicingSalutation = '';
			// // $user->invoicingName = new \Extras\Types\Name($x['client_name']);

			// if($x['client_email']) $user->invoicingEmail = new \Extras\Types\Email($x['client_email']);
			// if($x['client_phone']) $user->invoicingPhone = new \Extras\Types\Phone($x['client_phone']);
			// if($x['client_url']) $user->invoicingUrl = new \Extras\Types\Url($x['client_url']);

			// $user->invoicingAddress = new \Extras\Types\Address(array(
			// 	'address' => array_filter(array($x['client_address'], $x['client_address2'])),
			// 	'postcode' => $x['client_postcode'],
			// 	'locality' => $x['client_locality'],
			// 	'country' => $this->context->locationRepository->findOneBy(array('oldId'=>$x['client_country_id'], 'type'=>$locationTypeCountry)),
			// ));

			// $user->invoicingCompanyName = $x['client_company_name'];
			// $user->invoicingCompanyId = $x['client_company_id'];
			// $user->invoicingCompanyVatId = $x['client_company_vat_id'];


			$contacts = new \Extras\Types\Contacts();
			$contacts->add(new \Extras\Types\Email($x['email']));
			$contacts->add(new \Extras\Types\Phone($x['phone']));
			$user->contacts = $contacts;
			
			$user->language = $this->context->languageRepository->findOneByOldId($x['language_id']);
			$user->location = $this->context->locationRepository->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$locationTypeCountry));

			$this->model->persist($user);
		}
		$this->model->flush();
	}

	private function importPotentialOwners() {

		return true; //@todo - toto treba opravit este nefunguje

		$role = $this->context->userRoleRepository->findOneBySlug('potentialowner');
		$locationTypeCountry = $this->context->locationTypeRepository->findOneBySlug('country');

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
				$user = $this->context->userEntityFactory->create();
			} else if (count($existingUsers) == 1) {
				$user = \Service\User\User::get($existingUsers[0]);
			} else {
				eval('$user = \Service\User\User::merge('.implode(', ', $existingUsers).');');
			}


			if (!$user->login) $user->login = $x['email'];
			if (!$user->password) $user->password = NULL;
			//if (!$user->oldId) $user->oldId = $x['id'];

			$user->role = $role;

			// if (!$user->invoicingSalutation) $user->invoicingSalutation = $x['contact_salutation'];
			// if (!$user->invoicingName) $user->invoicingName = new \Extras\Types\Name($x['contact_firstname'], '', $x['contact_lastname']);

			// if (!$user->invoicingAddress) $user->invoicingAddress = new \Extras\Types\Address(array(
			// 	'address' => array_filter(array($x['address1'], $x['address2'])),
			// 	'postcode' => $x['postcode'],
			// 	'locality' => $x['locality'],
			// 	'country' => $this->context->locationRepository->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$locationTypeCountry)),
			// ));

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

			$user->language = $this->context->languageRepository->findOneByOldId($x['language_id']);
			$user->location = $this->context->locationRepository->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$locationTypeCountry));


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

			$this->model->persist($user);
		}
		$this->model->flush();
	}

	private function importVisitors() {

		return true; //@todo - toto treba opravit este nefunguje

	}
}