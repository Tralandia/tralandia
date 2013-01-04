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

		$role = $this->context->userRoleRepositoryAccessor->get()->findOneBySlug('superadmin');

		$admins = array(
			array('toth@tralandia.com', 'radkos'),
			array('durika@tralandia.com', 'davidheslo'),
			array('czibula@tralandia.com', 'kscibiks'),
			array('vaculciak@tralandia.com', 'branoheslo'),
		);

		$en = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');
		foreach ($admins as $key => $value) {
			// Rado
			$user = $this->context->userEntityFactory->create();
			$user->login = $value[0];
			$user->password = $value[1];
			$user->role = $role;


			if (\Nette\Utils\Validators::isEmail($value[0])) {
				$user->addEmail($this->context->contactEmailRepositoryAccessor->get()->createNew()->setValue($value[0]));
			}

			$user->language = $en;

			//d($user);
			$this->model->persist($user);
		}

		$this->model->flush();
		return TRUE;
	}

	private function importAdmins() {

		$role = $this->context->userRoleRepositoryAccessor->get()->findOneBySlug('admin');

		$r = q('select * from members_admins');
		while($x = mysql_fetch_array($r)) {
			$user = $this->context->userRepositoryAccessor->get()->findOneByLogin($x['email']);

			if ($user) {
				continue;
			}

			$user = $this->context->userEntityFactory->create();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];
			$user->role = $role;

			$user->addEmail($this->context->contactEmailRepositoryAccessor->get()->createNew()->setValue($x['email']));
			
			$user->language = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');
			$this->model->persist($user);
		}
		$this->model->flush();

	}

	private function importManagers() {

		$role = $this->context->userRoleRepositoryAccessor->get()->findOneBySlug('manager');

		$countryLocationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBySlug('country');

		$r = q('select * from members_managers');
		while($x = mysql_fetch_array($r)) {
			$user = $this->context->userRepositoryAccessor->get()->findOneByLogin($x['email']);

			if ($user) {
				continue;
			}

			$user = $this->context->userEntityFactory->create();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];
			$user->role = $role;

			$user->addEmail($this->context->contactEmailRepositoryAccessor->get()->createNew()->setValue($x['email']));
			
			$user->language = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');

			$assignedCountries = array_unique(array_filter(explode(',', $x['countries'])));
			$assignedLanguages = array_unique(array_filter(explode(',', $x['languages'])));

			foreach ($assignedCountries as $key => $value) {
				foreach ($assignedLanguages as $key2 => $value2) {
					$combination = $this->context->userCombinationEntityFactory->create();

					$combination->country = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$value, 'type'=>$countryLocationType));
					$combination->language = $this->context->languageRepositoryAccessor->get()->findOneByOldId($value2);
					$user->addCombination($combination);
				}
			}
			$this->model->persist($user);
		}
		$this->model->flush();
	}

	private function importTranslators() {

		$role = $this->context->userRoleRepositoryAccessor->get()->findOneBySlug('translator');

		$r = q('select * from members_translators');
		while($x = mysql_fetch_array($r)) {
			$user = $this->context->userRepositoryAccessor->get()->findOneByLogin($x['email']);

			if ($user) {
				continue;
			}

			$user = $this->context->userEntityFactory->create();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];
			$user->role = $role;
			// $user->invoicingData = @todo;

			$user->addEmail($this->context->contactEmailRepositoryAccessor->get()->createNew()->setValue($x['email']));
			
			$user->language = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');

			$details = array(
				'sourceLanguage' => $this->context->languageRepositoryAccessor->get()->findOneByOldId($x['language_from']),
				'pricePerStandardPage' => $x['price'],
				'pricePerTicketsStandardPage' => $x['tickets_price'],
			);
			$user->details = $details;

			$combination = $this->context->userCombinationEntityFactory->create();
			$combination->language = $this->context->languageRepositoryAccessor->get()->findOneByOldId($x['language_to']);
			$user->addCombination($combination);
			$this->model->persist($user);
		}
		$this->model->flush();
	}

	private function importOwners() {

		$role = $this->context->userRoleRepositoryAccessor->get()->findOneBySlug('owner');
		$locationTypeCountry = $this->context->locationTypeRepositoryAccessor->get()->findOneBySlug('country');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from members where country_id = 46');		
		} else {
			$r = q('select * from members');		
		}
		while($x = mysql_fetch_array($r)) {
			$user = $this->context->userRepositoryAccessor->get()->findOneByLogin($x['email']);

			if ($user) {
				continue;
			}

			$user = $this->context->userEntityFactory->create();

			$user->login = $x['email'];
			$user->password = $x['password'];
			$user->oldId = $x['id'];

			$user->role = $role;

			$invoicingData = $this->context->invoiceInvoicingDataRepositoryAccessor->get()->createNew();
			$invoicingData->name = $x['client_name'];
			$invoicingData->email = $x['client_email'];
			$invoicingData->phone = $x['client_phone'];
			$invoicingData->url = $x['client_url'];
			$invoicingData->address = implode("\n", array_filter(array(
				$x['client_address'], 
				$x['client_address2'],
				$x['client_postcode'],
				$x['client_locality'],
			)));

			$invoicingData->primaryLocation = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$x['client_country_id'], 'type'=>$locationTypeCountry));

			$invoicingData->companyName = $x['client_company_name'];
			$invoicingData->companyId = $x['client_company_id'];
			$invoicingData->companyVatId = $x['client_company_vat_id'];
			$this->model->persist($invoicingData);

			$user->invoicingData = $invoicingData;

			$user->addEmail($this->context->contactEmailRepositoryAccessor->get()->createNew()->setValue($x['email']));

			if (strlen($x['phone'])) {
				if ($tempPhone = $this->context->phoneBook->getOrCreate($x['phone'])) {
					$user->addPhone($tempPhone);
				}	
			}

			$user->language = $this->context->languageRepositoryAccessor->get()->findOneByOldId($x['language_id']);
			$user->primaryLocation = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$locationTypeCountry));

			$user->newsletterNews = (bool)$x['newsletter_news'];
			$user->newsletterMarketing = (bool)$x['newsletter_marketing'];
			$this->model->persist($user);
		}
		$this->model->flush();
	}

	// private function importPotentialOwners() {

	// 	return true; //@todo - toto treba opravit este nefunguje

	// 	$role = $this->context->userRoleRepositoryAccessor->get()->findOneBySlug('potentialowner');
	// 	$locationTypeCountry = $this->context->locationTypeRepositoryAccessor->get()->findOneBySlug('country');

	// 	if ($this->developmentMode == TRUE) {
	// 		$r = q('select * from contacts where country_id = 46 limit 10000');	
	// 	} else {
	// 		$r = q('select * from contacts');
	// 	}

	// 	while($x = mysql_fetch_array($r)) {
	// 		$user = $this->context->userEntityFactory->create();

	// 		$r1 = q('select email from contacts_emails where contact_id = '.$x['id']);
	// 		while($x1 = mysql_fetch_array($r1)) {
	// 			$user->addEmail($this->context->contactEmailRepositoryAccessor->get()->createNew()->setValue($x1['email']));
	// 		}

	// 		$r1 = q('select phone from contacts_phones where contact_id = '.$x['id']);
	// 		while($x1 = mysql_fetch_array($r1)) {
	// 			if (strlen($x1['phone'])) {
	// 				if ($tempPhone = $this->context->phoneBook->getOrCreate($x1['phone'])) {
	// 					$user->addPhone($tempPhone);
	// 				}
	// 			}
	// 		}

	// 		if (!$user->login) $user->login = $x['email'];
	// 		if (!$user->password) $user->password = NULL;
	// 		//if (!$user->oldId) $user->oldId = $x['id'];

	// 		$user->role = $role;

	// 		// if (!$user->invoicingSalutation) $user->invoicingSalutation = $x['contact_salutation'];
	// 		// if (!$user->invoicingName) $user->invoicingName = new \Extras\Types\Name($x['contact_firstname'], '', $x['contact_lastname']);

	// 		// if (!$user->invoicingAddress) $user->invoicingAddress = new \Extras\Types\Address(array(
	// 		// 	'address' => array_filter(array($x['address1'], $x['address2'])),
	// 		// 	'postcode' => $x['postcode'],
	// 		// 	'locality' => $x['locality'],
	// 		// 	'country' => $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$locationTypeCountry)),
	// 		// ));

	// 		$user->subscribed = !(bool)$x['unsubscribed'];
	// 		$user->banned = (bool)$x['banned'];
	// 		$user->full = (bool)$x['full'];
	// 		$user->spam = (bool)$x['spam'];

	// 		$user->addPhone($this->context->contactPhoneRepositoryAccessor->get()->createNew()->setValue($x['phone']));


	// 		$contacts = new \Extras\Types\Contacts();

	// 		$user->addEmail($this->context->contactUrlRepositoryAccessor->get()->createNew()->setValue($x['url']));

	// 		foreach ($contacts as $key => $value) {
	// 			if(Validators::isEmail($value)) {
	// 				$user->addEmail($this->context->contactEmailRepositoryAccessor->get()->createNew()->setValue($value));
	// 			} else {
	// 				$user->addPhone($this->context->contactPhoneRepositoryAccessor->get()->createNew()->setValue($value));
	// 			}
	// 		}
			
	// 		$user->language = $this->context->languageRepositoryAccessor->get()->findOneByOldId($x['language_id']);
	// 		$user->location = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$locationTypeCountry));


	// 		$user->currentTelmarkOperator = $x['telmark_operator_id'];

	// 		$details = array(
	// 			'counter' => $x['counter'],
	// 			'done' => $x['done'],
	// 			'done_stamp' => $x['done_stamp'],
	// 			'status' => $x['status'],
	// 			'call_count' => $x['call_count'],
	// 		);
	// 		$user->details = $details;
			
	// 		debug($user); return;

	// 		$this->model->persist($user);
	// 	}
	// 	$this->model->flush();
	// }

	private function importVisitors() {

		return true; //@todo - toto treba opravit este nefunguje

	}
}