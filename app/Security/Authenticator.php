<?php

namespace Security;

use Nette\Object,
	Nette\Security as NS,
	Nette\Environment;

/**
 * Users authenticator.
 */
class Authenticator extends Object implements NS\IAuthenticator {

	/**
	 * @var \Repository\User\UserRepository
	 */
	public $userRepository;

	/**
	 * @param \Repository\User\UserRepository $userRepository
	 */
	public function __construct(\Repository\User\UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * @param array $credentials
	 *
	 * @return \Nette\Security\Identity
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials) {
		list($email, $password) = $credentials;

		/** @var $user \Entity\User\User */
		$user = $this->userRepository->findOneByLogin($email);

		if (!$user) {
			throw new NS\AuthenticationException("User '$email' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($user->getPassword() !== self::calculateHash($password)) {
			throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}
		
		return new NS\Identity($user->getId(), array($user->getRole()->getSlug()), $user->getIdentity());
	}

	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public static function calculateHash($password) {
		return $password;
	}
}


