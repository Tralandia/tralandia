<?php

namespace Security;

use Nette\Environment,
	Nette\Security\Permission;

class MyAssertion {
	
	public function test(Permission $acl, $role, $resource, $privilege) {
		debug(func_get_args());
		debug($acl->getQueriedRole()->getUser());
		
		return $acl->getQueriedResource()->supported;
	}
}