<?php

namespace Service\User;


class User extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\User\User';

	public function setPassword($password) {
		$this->getMainEntity()->password = md5($password);
	}

	public function getIdentity() {
		$identity = array();

		$identity = iterator_to_array($this->getMainEntity());
		if ($this->role) {
			$identity['homePage'] = $this->role->homePage;
		} else {
			$identity['homePage'] = NULL;
		}
		unset($identity['password']);

		return $identity;
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
		
		//usort($users, "self::sortMergedUsers");
		for($i = count($users)-1; $i > 0; $i--) {
			debug($users[$i-1]->id.' merging with '.$users[$i]->id);
			self::mergeTwoUsers($users[$i-1], $users[$i]);
		}

		return $users[0];
	}

	public static function getOrCreate(\Extras\Types\Email $email) {
		#@todo - dorobit, aby to hladalo na tej cache tabulke, kde su vsetky kontakty userov
		$user = self::getByLogin($email);

		if (!$user) {
			$user = \Service\User\User::get();
			$user->login = $email;
			$user->save();
		}

		return $user;
	}

	private static function mergeTwoUsers($user1, $user2) {
		$t = \Nette\Reflection\ClassType::from('\Entity\User\User');
		$t = $t->getProperties();
		foreach ($t as $key => $value) {
			$propertyType = $value->getAnnotation('var');
			$propertyName = $value->name;
			if ($propertyType == 'Collection' && ($value->hasAnnotation('ORM\ManyToMany') || $value->hasAnnotation('ORM\OneToMany'))) {
				$singularPropertyName = $value->getAnnotation('UI\SingularName');
				$getMethodName = 'get'.ucfirst($propertyName);
				$addMethodName = 'add'.ucfirst($singularPropertyName);
				$removeMethodName = 'remove'.ucfirst($singularPropertyName);
				$collection = $user2->$getMethodName();
				if ($collection === NULL) continue;
				//if ($singularPropertyName == 'role') debug($user1->$getMethodName()->toArray());
				foreach ($collection as $key2 => $value2) {
					$user1->$addMethodName($value2);
					$user2->$removeMethodName($value2);
				}
			} else {
				if ($user2->$propertyName && $user1->$propertyName === NULL) {
					$user1->$propertyName = $user2->$propertyName;
				}
			}
			//debug($value); //return;
		}
		//debug($user1);
		//debug($user1->roles->toArray());
		$user1->save();
		$user2->delete();
		return;
	} 

	private static function sortMergedUsers($a, $b) {
		return $a->updated > $b->updated ? -1 : 1; //@todo - upravit tak, aby to bral aj podla ACL - vyzsia rola ma vyzsie priority, rovnaka rola ide podla "updated"
	}



	protected function postSave() {
		\Service\ContactCacheList::syncContacts($this->contacts, $this->getMainEntityName(), $this->id);
	}
}