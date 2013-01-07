<?php

namespace FormHandler;

use Repository\User\UserRepository;

class RegistrationHandler extends FormHandler
{

	public $onInvoiceExists = array();
	public $onInvoiceNotExists = array();

	/**
	 * @var \Repository\User\UserRepository
	 */
	protected $userRepository;


	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function handleSuccess($values)
	{
		$error = new ValidationError;

		// User
		$user = $this->userRepository->findByEmail($values->email);
		if($user) {
			$error->addError("Email exists", 'email');
		}
		$error->assertValid();

		/** @var $user \Entity\User\User */
		$user = $this->userRepository->createNew();
		$user->setLogin($values->login);
		$user->setPassword($values->password);

		// process data

		$this->model->save($values);
	}


//	public function handleSuccess($values)
//	{
//		$error = new ValidationError;
//
//		if ($values->name != "Valid") {
//			$error->addError("Name is invalid", 'name');
//		}
//
//		$error->assertValid();
//		$this->model->save($values);
//	}
}