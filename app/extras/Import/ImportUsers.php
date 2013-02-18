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
			
			$user->language = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');
			$this->model->persist($user);
		}
		$this->model->flush();

	}

	private function importManagers() {

		$role = $this->context->userRoleRepositoryAccessor->get()->findOneBySlug('admin');

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
			
			$user->language = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');
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
			
			$user->language = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');

			$details = array(
				'language' => $this->context->languageRepositoryAccessor->get()->findOneByOldId($x['language_to']),
				'pricePerStandardPage' => $x['price'],
			);
			$user->details = $details;

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

			$user->language = $this->context->languageRepositoryAccessor->get()->findOneByOldId($x['language_id']);
			$user->primaryLocation = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$locationTypeCountry));

			$user->newsletter = (bool)($x['newsletter_news'] || $x['newsletter_marketing']);
			$this->model->persist($user);
		}
		$this->model->flush();
	}

	private function importVisitors() {

		return true; //@todo - toto treba opravit este nefunguje

	}
}