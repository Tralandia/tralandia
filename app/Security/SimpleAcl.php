<?php

namespace Security;

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

		foreach($resources as $resource) {
			$this->addResource($resource);
		}

		$this->allow('owner', $ownerModule);
		$this->allow('owner', $rentalEntity, self::ALL, array($assertion, 'owner'));
		$this->allow('superadmin');
	}

}