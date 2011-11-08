<?php

use Nette;

class SpeedUploadAuthenticator extends Nette\Object implements Nette\Security\IAuthenticator {
	
	private $userlist;
	private $identity = null;
	
	public function __construct(array $userlist, Nette\Security\Identity $identity = null) {
		$this->userlist = $userlist;
		$this->identity = $identity;
	}
	
	public function authenticate(array $credentials) {
		list($username, $password) = $credentials;
		foreach ($this->userlist as $name => $pass) {
			if (strcasecmp($name, $username) === 0) {
				if ((string) $pass === (string) $password) {
					if ($this->identity !== null) {
						return $this->identity;
					} else {
						return new Identity($name);
					}
				} else {
					throw new AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
				}
			}
		}
		throw new AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
	}
}
