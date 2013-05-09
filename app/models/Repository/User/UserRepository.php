<?php
namespace Repository\User;

use Entity\User\Role;

/**
 * UserRepository class
 *
 * @author Dávid Ďurika
 */
class UserRepository extends \Repository\BaseRepository {


	public function findTranslatorsForSelect()
	{
		return $this->findByRoleForSelect(Role::TRANSLATOR);
	}


	public function findByRole($role)
	{
		if($role instanceof Role) {
			return parent::findByRole($role);
		}

		$role = $this->related('role')->findBySlug($role);
		return parent::findByRole($role);
	}

	protected function findByRoleForSelect($role)
	{
		$rows = $this->findByRole($role);
		foreach ($rows as $row) {
			$return[$row->id] = $row->login;
		}
		asort($return);
		return $return;
	}

}
