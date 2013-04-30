<?php

namespace Security;


use Entity\User\User;
use Nette\Security as NS;

class Identity extends NS\Identity
{

	/**
	 * @return bool
	 */
	public function isFake()
	{
		return $this instanceof FakeIdentity;
	}


	/**
	 * @param User $user
	 *
	 * @return Identity
	 */
	public static function createIdentity(User $user)
	{
		return new self($user->getId(), array($user->getRole()->getSlug()), $user->getIdentityData());
	}

}
