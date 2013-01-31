<?php

namespace Security;

use Nette\Security\Permission;

class MyAssertion {

	/**
	 * @var \Nette\Security\User
	 */
	protected $user;

	/**
	 * @param \Nette\Security\User $user
	 */
	public function __construct(\Nette\Security\User $user)
	{
		$this->user = $user;
	}

	/**
	 * @param \Nette\Security\Permission $acl
	 * @param $role
	 * @param $resource
	 * @param $privilege
	 *
	 * @return bool
	 */
	public function owner(Permission $acl, $role, $resource, $privilege) {
		$iResource = $acl->getQueriedResource();

		if($iResource instanceof IOwnerable) {
			return $iResource->getOwnerId() == $this->user->getId();
		}
		
		return FALSE;
	}
}