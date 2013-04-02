<?php

namespace User;

use Environment\Environment;
use Repository\User\UserRepository;

class FindOrCreateUser {

	/**
	 * @var \Repository\User\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var UserCreator
	 */
	protected $userCreator;


	/**
	 * @param UserRepository $userRepository
	 * @param UserCreator $userCreator
	 */
	public function __construct(UserRepository $userRepository, UserCreator $userCreator)
	{
		$this->userRepository = $userRepository;
		$this->userCreator = $userCreator;
	}


	/**
	 * @param $email
	 * @param Environment $environment
	 * @param null $role
	 *
	 * @return \Entity\User\User
	 */
	public function getUser($email, Environment $environment, $role = NULL)
	{
		$user = $this->userRepository->findOneByLogin($email);
		if(!$user) {
			$user = $this->userCreator->create($email, $environment, $role);
		}

		return $user;
	}
}
