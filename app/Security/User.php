<?php

namespace Security;

use Nette;

class User extends Nette\Security\User
{

	/**
	 * Has a user effective access to the Resource?
	 * If $resource is NULL, then the query applies to all resources.
	 * @param  string  resource
	 * @param  string  privilege
	 * @return bool
	 */

	public function isAllowed($resource = IAuthorizator::ALL, $privilege = IAuthorizator::ALL)
	{
		$authorizator = $this->getAuthorizator();
		foreach ($this->getRoles() as $role) {
			if ($authorizator->isAllowed(new Role($role, $this), $resource, $privilege)) {
				return TRUE;
			}
		}

		return FALSE;
	}


}