<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/2/13 2:40 PM
 */

namespace Tralandia\Security;


use Doctrine\ORM\EntityManager;
use Nette;
use Nette\Security\IAuthenticator;
use Nette\Security\IAuthorizator;
use Nette\Security\IUserStorage;

class User extends Nette\Security\User {


	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	/**
	 * @var \Entity\User\User
	 */
	private $entity;


	public function __construct(IUserStorage $storage, IAuthenticator $authenticator = NULL, IAuthorizator $authorizator = NULL, EntityManager $em)
	{
		$this->em = $em;
		parent::__construct($storage, $authenticator, $authorizator);
	}


	/**
	 * @return \Entity\User\User
	 */
	public function getEntity()
	{
		if($this->isLoggedIn() && !$this->entity) {
			$entity = $this->em->getRepository(USER_ENTITY)->find($this->getId());
			$this->entity = $entity;
		}

		return $this->entity;
	}

}
