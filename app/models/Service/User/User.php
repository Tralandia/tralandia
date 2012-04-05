<?php

namespace Service\User;


class User extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\User\User';

	public function setPassword($password) {
		$this->getMainEntity()->password = md5($password);
	}
	
}
