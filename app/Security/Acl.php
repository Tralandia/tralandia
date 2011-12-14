<?php

namespace Security;

use Nette\Environment,
	Nette\Security\Permission;

class Acl extends Permission {
    public function __construct() {
		$assertion = new MyAssertion;
		
		// definovanie rolí
		$this->addRole('guest');
		$this->addRole('member');
		$this->addRole('admin', 'member');
		
		// definovanie vsetkych zdrojov
		$this->addResource('Rental');
		
		// definovanie prav pre uzivatela
//		$this->allow('guest', 'Rental', 'show', array($assertion, 'test'));
		$this->allow('admin', 'Rental', 'show', array($assertion, 'test'));
		
		// definovanie prav pre admina
		//$this->allow('admin', Permission::ALL, Permission::ALL);

    }
}