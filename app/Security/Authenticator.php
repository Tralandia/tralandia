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
	 * Performs an authentication
	 * @param  array
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials) {
		list($email, $password) = $credentials;

		// $row = $this->users->findOneBy(array('email' => $email));
		$user = \Service\User\User::getByLogin($email);

		if (!$user) {
			throw new NS\AuthenticationException("User '$email' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($user->password !== $this->calculateHash($password)) {
			throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}
		
		return new NS\Identity($user->id, $user->roles->toArray(), $user->getIdentity());
	}

	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public function calculateHash($password) {
		return md5($password);
	}
}


