<?php

namespace Service\User;

use Extras\Models\ServiceList;

class RoleList extends ServiceList {

	const MAIN_ENTITY_NAME = '\Entity\User\Role';

	public static function forAcl() {
		return \Service\User\RoleList::getPairs('id', 'slug', null, array('id' => 'ASC'), 9);
	}

}