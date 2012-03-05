<?php

namespace Security;

use Nette\Object,
	Nette\Security as NS,
	Nette\Environment;

/**
 * Users authenticator.
 */
class Authenticator extends Object implements NS\IAuthenticator {
	
	/** @var Nette\Database\Table\Selection */
	private $users;
	
	public function __construct(\Doctrine\ORM\EntityRepository $users) {
		$this->users = $users;
	}

	/**
	 * Performs an authentication
	 * @param  array
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials) {
		list($email, $password) = $credentials;
		$row = $this->users->findOneBy(array('email' => $email));

		if (!$row) {
			throw new NS\AuthenticationException("User '$email' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($row->password !== self::calculateHash($password)) {
			throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}
		
		//unset($row->password);
		return new NS\Identity($row->id, $row->role, $row);
	}

	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public static function calculateHash($password) {
		return sha1($password . str_repeat(Environment::getConfig('security')->salt, 10));
	}
}
