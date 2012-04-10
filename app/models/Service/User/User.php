<?php

namespace Service\User;


class User extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\User\User';

	public function setPassword($password) {
		$this->getMainEntity()->password = md5($password);
	}
	
	public static function merge() {
		$users = func_get_args();
		foreach ($users as $key => $value) {
			$users[$key] = \Service\BaseService::getAsService($value, '\Service\User\User');
		}

		if (count($users) < 2) {
			throw new \Nette\InvalidArgumentException('This function needs at least 2 parameters of \Service\User\User or \Entity\User\User');
			return FALSE;
		}
		usort($users, "self::sortMergedUsers");
		for($i = count($users)-1; $i > -1; $i--) {
			debug($user[$i-1]->id.' merging with '.$user[$i]->id);
			self::mergeTwoUsers($users[$i-1], $users[$i]);
		}
	}

	private static function mergeTwoUsers($user1, $user2) {
		return $user1;
	} 

	private static function sortMergetUsers($a, $b) {
		return $a->updated > $b->updated ? -1 : 1;
	}
}
