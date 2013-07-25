<?php

namespace Security;

use Entity\User\User;
use Nette\Object,
	Nette\Security as NS,
	Nette\Environment;

/**
 * Users authenticator.
 */
class Authenticator extends Object implements NS\IAuthenticator
{

	/**
	 * @var \Repository\User\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var string
	 */
	protected static $autoLoginDelimiter = '-';


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
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		$user = $this->userRepository->findOneByLogin($email);

		if (!$user) {
			throw new NS\AuthenticationException("User '$email' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($user->getPassword() !== self::calculatePasswordHash($password)) {
			throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}


		return $this->getIdentity($user);
	}


	/**
	 * @param User $user
	 *
	 * @return Identity
	 */
	public function getIdentity(User $user)
	{
		return Identity::createIdentity($user);
	}


	public function fakeAuthenticate(User $user, User $originalUser)
	{
		return FakeIdentity::createFakeIdentity($user, $originalUser);
	}


	/**
	 * Computes salted password hash.
	 *
	 * @param  string
	 *
	 * @return string
	 */
	public static function calculatePasswordHash($password)
	{
		return $password;
	}


	/**
	 * @param \Entity\User\User $user
	 *
	 * @return string
	 */
	public static function calculateAutoLoginHash(\Entity\User\User $user)
	{
		return $user->getId() . self::$autoLoginDelimiter . substr(sha1($user->getPassword() . 'dkh43k5h3k2o9'), 0, 16);
	}


	/**
	 * @param $autologin
	 *
	 * @return \Nette\Security\Identity
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function autologin($autologin)
	{
		list($userId,) = explode(self::$autoLoginDelimiter, $autologin, 2);
		if (!$user = $this->userRepository->find($userId)) {
			throw new NS\AuthenticationException("Invalid autologin link.");
		}
		$autologinHash = $this->calculateAutoLoginHash($user);
		if ($autologin != $autologinHash) {
			throw new NS\AuthenticationException("Invalid autologin link.");
		}

		return $this->getIdentity($user);
	}
}


