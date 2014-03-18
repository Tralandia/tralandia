<?php

namespace Security;

use Entity\User\User;
use Nette\Object,
	Nette\Security as NS,
	Nette\Environment;
use Tralandia\BaseDao;

/**
 * Users authenticator.
 */
class Authenticator extends Object implements NS\IAuthenticator
{


	/**
	 * @var string
	 */
	protected static $autoLoginDelimiter = '-';

	/**
	 * @var string
	 */
	protected static $passwordSalt = 'salt45hfhd34lp';

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $userDao;


	/**
	 * @param BaseDao $userDao
	 */
	public function __construct(BaseDao $userDao)
	{
		$this->userDao = $userDao;
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

		$user = $this->userDao->findOneByLogin($email);

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


	/**
	 * @param User $user
	 * @param User $originalUser
	 *
	 * @return FakeIdentity
	 */
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
		return sha1($password . self::$passwordSalt);
	}


	/**
	 * @param \Entity\User\User $user
	 *
	 * @return string
	 */
	public static function calculateAutoLoginHash(\Entity\User\User $user)
	{
		return $user->getId() . self::$autoLoginDelimiter . substr(sha1($user->getPassword() . 'dh3k2f5hu43k5'), 0, 6);
	}


	/**
	 * @param $autologin
	 *
	 * @return \Nette\Security\Identity
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function autologin($autologin)
	{
		$user = $this->getUserFromHash($autologin);
		if ($user && $autologin != $this->calculateAutoLoginHash($user)) {
			throw new NS\AuthenticationException("Invalid autologin link.");
		}

		return $this->getIdentity($user);
	}


	/**
	 * @param User $user
	 *
	 * @return string
	 */
	public static function calculateNewPasswordHash(\Entity\User\User $user)
	{
		return $user->getId() . self::$autoLoginDelimiter . substr(sha1($user->getPassword() . 'salt77fh5dk30s92'), 0, 6);
	}


	/**
	 * @param $hash
	 *
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function checkNewPasswordHash($hash)
	{
		$user = $this->getUserFromHash($hash);
		if ($user && $hash != $this->calculateNewPasswordHash($user)) {
			throw new NS\AuthenticationException("Invalid autologin link.");
		}
	}


	/**
	 * @param $hash
	 *
	 * @return mixed
	 */
	public function getUserFromHash($hash)
	{
		list($userId,) = explode(self::$autoLoginDelimiter, $hash, 2);
		return $this->userDao->find($userId);
	}
}


