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

	public function getUser() {
		return $this->user;
	}

	
	public function getRoleId() {
		return $this->role;
	}

}