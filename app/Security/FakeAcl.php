<?php

namespace Security;

use Entity\User\Role as RoleEntity;
use Nette\Caching\Cache,
	Nette\Utils\Finder,
	Nette\Utils\Strings,
	Nette\Security\Permission;


class FakeAcl extends Permission
{

	public function buildAssertions() {}

}