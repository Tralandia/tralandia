<?php

namespace Security;

use Nette\Security\IRole;

class Role extends \Nette\Object implements IRole {

	protected $role = NULL;
	protected $user = NULL;

	public function __construct($role, $user) {
		$this->role = $role;
		$this->user = $user;
	}
	
	public function getRole() {
		return $this->role;
	}

	public function getRoleId() {
		if($this->role instanceof \Entity\User\Role) {
			return $this->role->slug;
		} else {
			return $this->role;
		}
	}

	public function getUser() {
		return $this->user;
	}	

}