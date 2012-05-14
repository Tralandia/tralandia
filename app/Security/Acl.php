<?php

namespace Security;

use Nette\Environment,
	Nette\Security\Permission;

class Acl extends Permission {

	public function setup(array $roles,array $resources,array $rules) {
		// debug(func_get_args());

		# roles
		foreach ($roles as $role) {
			call_user_func_array(array($this, 'addRole'), (array) $role);
		}

		# resources
		foreach ($resources as $resource) {
			call_user_func_array(array($this, 'addResource'),(array) $resource);
		}

		# rules
		foreach ($rules as $type => $ruleSet) {
			if(!is_array($ruleSet)) continue;
			foreach ($ruleSet as $rule) {
				call_user_func_array(array($this, $type), $rule);
			}
		}

	}
}