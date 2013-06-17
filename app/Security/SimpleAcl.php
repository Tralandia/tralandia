<?php

namespace Security;

use Entity\User\Role as RoleEntity;
use Nette\Caching\Cache,
	Nette\Utils\Finder,
	Nette\Utils\Strings,
	Nette\Security\Permission;

class SimpleAcl extends Permission
{

	const FAKE_IDENTITY = 'fakeIdentity';

	/**
	 * @var \Repository\User\RoleRepository
	 */
	protected $roleRepository;


	public function __construct(\Repository\User\RoleRepository $roleRepository)
	{
		$this->roleRepository = $roleRepository;
	}


	public function buildAssertions(\Nette\Security\User $user)
	{
		$assertion = new MyAssertion($user);

		$roles = $this->roleRepository->findAll();
		/** @var $role \Entity\User\Role */
		foreach ($roles as $role) {
			$parent = NULL;
			if ($role->hasParent()) {
				$parent = $role->getParent()->getSlug();
			}
			$slug = $role->getSlug();
			$this->addRole($slug, $parent);
		}

		$resources = [];
		$resources[] = $ownerModule = 'OwnerModule';
		$resources[] = $adminModule = 'AdminModule';

		$resources[] = $signPresenter = 'Front:Sign';
		$resources[] = $registrationPresenter = 'Front:Registration';

		$resources[] = 'Owner:Rental';
		$resources[] = 'Admin:Currency';
		$resources[] = 'Admin:DictionaryManager';
		$resources[] = 'Admin:EntityGenerator';
		$resources[] = 'Admin:Rental';
		$resources[] = 'Admin:User';
		$resources[] = 'Admin:RunRobot';
		$resources[] = 'Admin:Language';
		$resources[] = 'Admin:RentalType';
		$resources[] = 'Admin:RentalAmenity';
		$resources[] = 'Admin:Country';
		$resources[] = 'Admin:Region';
		$resources[] = 'Admin:Locality';
		$resources[] = 'Admin:Domain';
		$resources[] = 'Admin:Cache';
		$resources[] = $phrasePresenter = 'Admin:Phrase';
		$resources[] = $phraseListPresenter = 'Admin:PhraseList';

		$resources[] = $userEntity = 'Entity\User\User';
		$resources[] = $rentalEntity = 'Entity\Rental\Rental';
		$resources[] = $translationEntity = 'Entity\Phrase\Translation';

		foreach ($resources as $resource) {
			$this->addResource($resource);
		}

		$this->allow(self::ALL, [$signPresenter, $registrationPresenter], self::ALL);

		$this->deny(RoleEntity::LOGGED, [$signPresenter, $registrationPresenter], self::ALL);
		$this->allow(RoleEntity::LOGGED, $signPresenter, 'out');
		$this->allow(RoleEntity::LOGGED, self::ALL, 'restoreOriginalIdentity');

		$this->allow(RoleEntity::OWNER, $ownerModule);
		$this->allow(RoleEntity::OWNER, $rentalEntity, self::ALL, [$assertion, 'owner']);

		$this->allow(RoleEntity::TRANSLATOR, $adminModule);
		$this->allow(RoleEntity::TRANSLATOR, $phrasePresenter, 'editList');
		$this->allow(RoleEntity::TRANSLATOR, $phraseListPresenter, ['edit', 'toTranslate', 'search']);
		$this->allow(RoleEntity::TRANSLATOR, $translationEntity, 'translate', [$assertion, 'translate']);

		//$this->allow(RoleEntity::ADMIN, $userEntity, self::FAKE_IDENTITY, [$assertion, 'fakeIdentity']);

		$this->allow(RoleEntity::SUPERADMIN);

	}

}
