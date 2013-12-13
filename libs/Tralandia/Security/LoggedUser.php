<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/2/13 2:37 PM
 */

namespace Tralandia\Security;


use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Nette;

class LoggedUser {


	/**
	 * @var \Nette\Security\User
	 */
	private $user;


	private $userEntity;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	public function __construct(Nette\Security\User $user, EntityManager $em)
	{
		$this->user = $user;
		$this->em = $em;
	}


	/**
	 * @return Nette\Security\User
	 */
	public function getUser()
	{
		return $this->user;
	}


	public function getUserEntity()
	{
		if(!$this->userEntity) {

		}
	}


}
