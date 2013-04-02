<?php

namespace User;


use Doctrine\ORM\EntityManager;
use Entity\User\Role;
use Environment\Environment;
use Nette\Utils\Strings;
use Repository\User\UserRepository;

class UserCreator {

	/**
	 * @var \Repository\User\UserRepository
	 */
	protected $userRepository;

	protected $roleRepository;


	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{

		$this->userRepository = $em->getRepository(USER_ENTITY);
		$this->roleRepository = $em->getRepository(USER_ROLE_ENTITY);
	}


	/**
	 * @param $email
	 * @param Environment $environment
	 * @param string $role
	 *
	 * @return \Entity\User\User
	 * @throws \Exception
	 */
	public function create($email, Environment $environment, $role = Role::GUEST)
	{
		/** @var $role \Entity\User\Role */
		$role = $this->roleRepository->findOneBySlug($role);

		if(!$role) {
			throw new \Exception("Role '$role' not found!");
		}

		/** @var $user \Entity\User\User */
		$user = $this->userRepository->createNew();

		$user->setPrimaryLocation($environment->getPrimaryLocation());
		$user->setLanguage($environment->getLanguage());

		$user->setLogin($email);
		$user->setPassword(Strings::random());
		$user->setRole($role);

		return $user;
	}

}
