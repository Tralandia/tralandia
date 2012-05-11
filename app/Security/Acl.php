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
		$this->addResource('Admin:Location');
		$this->addResource('Admin:Language');
		$this->addResource('Entity\Dictionary\Language');
		
		// definovanie prav pre uzivatela
		$this->allow('guest', array('Admin:Location'), array('list', 'edit'));
		$this->allow('guest', array('Admin:Language'), array('list', 'edit'));
		$this->allow('guest', 'Entity\Dictionary\Language', 'supported', array($assertion, 'test'));
		// $this->allow('admin', 'Rental', 'show', array($assertion, 'test'));
		
		// definovanie prav pre admina
		//$this->allow('admin', Permission::ALL, Permission::ALL);

    }
}