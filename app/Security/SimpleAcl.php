<?php

namespace Security;

use Entity\User\Role as RoleEntity;
use Nette\Caching\Cache,
	Nette\Utils\Finder,
	Nette\Utils\Strings,
	Nette\Security\Permission;

class SimpleAcl extends Permission {

	public function __construct(\Nette\Security\User $user,\Repository\User\RoleRepository $roleRepository)
	{
		$assertion = new MyAssertion($user);

		$roles = $roleRepository->forAcl();
		foreach($roles as $role) {
			$this->addRole($role);
		}

		$resources = [];
		$resources[] = $ownerModule = 'OwnerModule';
		$resources[] = $adminModule = 'AdminModule';

		$resources[] = $rentalEntity = 'Entity\Rental\Rental';
		$resources[] = $translationEntity = 'Entity\Phrase\Translation';

		foreach($resources as $resource) {
			$this->addResource($resource);
		}

		$this->allow(RoleEntity::OWNER, $ownerModule);
		$this->allow(RoleEntity::OWNER, $rentalEntity, self::ALL, [$assertion, 'owner']);

		$this->allow(RoleEntity::TRANSLATOR, $translationEntity, 'translate', [$assertion, 'translate']);

		$this->allow(RoleEntity::SUPERADMIN);
	}

}