<?php

namespace Security;

use Entity\User\User;

class FakeIdentity extends Identity {

	const ORIGINAL_IDENTITY = 'originalIdentity';

	/**
	 * @var Identity
	 */
	protected $originalIdentity;


	/**
	 * @param $id
	 * @param User $originalUser
	 * @param null $fakeRoles
	 * @param null $fakeData
	 */
	public function __construct($id, User $originalUser, $fakeRoles = NULL, $fakeData = NULL)
	{
		$originalIdentity = Identity::createIdentity($originalUser);
		$fakeData[self::ORIGINAL_IDENTITY] = $originalIdentity;

		parent::__construct($id, $fakeRoles, $fakeData);
	}


	/**
	 * @return Identity
	 */
	public function getOriginalIdentity()
	{
		if(!$this->originalIdentity) {
			$this->originalIdentity = $this->getData()[self::ORIGINAL_IDENTITY];
		}

		return $this->originalIdentity;
	}


	/**
	 * @param User $user
	 * @param User $originalUser
	 *
	 * @return FakeIdentity
	 */
	public static function createFakeIdentity(User $user, User $originalUser)
	{
		return new self($user->getId(), $originalUser, array($user->getRole()->getSlug()), $user->getIdentityData());
	}
}
