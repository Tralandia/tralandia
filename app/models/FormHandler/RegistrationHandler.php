<?php

namespace FormHandler;

use Repository\User\UserRepository;
use Repository\Rental\RentalRepository;

class RegistrationHandler extends FormHandler
{

	public $onInvoiceExists = array();
	public $onInvoiceNotExists = array();

	/**
	 * @var \Repository\User\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var \Repository\Rental\RentalRepository
	 */
	protected $rentalRepository;

	/**
	 * @param \Repository\User\UserRepository $userRepository
	 * @param \Repository\Rental\RentalRepository $rentalRepository
	 */
	public function __construct(UserRepository $userRepository, RentalRepository $rentalRepository)
	{
		$this->userRepository = $userRepository;
		$this->rentalRepository = $rentalRepository;
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

		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepository->createNew();
		$rental->setName();


		//$this->model->save($values);
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