<?php

namespace Tra\Services;

use Tra;

class User extends BaseService {

	const MAIN_ENTITY_NAME = 'User';
	
	public function prepareForm($form) {
		//$user = '\User';
		$reflector = $this->getReflector();
		//$reflector->allow($user, array('id', 'login'));
		//$reflector->allow($user, array('active', 'login', 'password'));
		//$reflector->except($user, array('active'));
		$reflector->extend($form, '\User');
	}

	public function sendEmail($type = NULL) {
		
	}
}
