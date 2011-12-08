<?php

namespace Security;

use Nette\Environment,
	Nette\Security\Permission;

class MyAssertion {
	
	public function test(Permission $acl, $role, $resource, $privilege) {

		//debug($role);
		//debug($acl->getQueriedResource());
		//debug($acl, $role, $resource, $privilege);
		
		
		return false;
		return $acl->getQueriedRole()->ownerId === $acl->getQueriedResource()->ownerId;
	}
}