<?php

namespace FormHandler;

use Entity\User\Role;
use Service\Contact\AddressCreator;
use Service\Rental\RentalCreator;
use Doctrine\ORM\EntityManager;

class RegistrationHandler extends FormHandler
{
	public $onSuccess = [];

	/**
	 * @var \Service\Rental\RentalCreator
	 */
	protected $rentalCreator;

	/**
	 * @var \Service\Contact\AddressCreator
	 */
	protected $addressCreator;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Entity\Rental\Rental
	 */
	public $rental;


	/**
	 * @param \Service\Rental\RentalCreator $rentalCreator
	 * @param \Service\Contact\AddressCreator $addressCreator
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(RentalCreator $rentalCreator, AddressCreator $addressCreator, EntityManager $em)
	{
		$this->rentalCreator = $rentalCreator;
		$this->addressCreator = $addressCreator;
		$this->em = $em;
	}

	public function handleSuccess($values)
	{
		$userRepository = $this->em->getRepository(USER_ENTITY);
		$userRoleRepository = $this->em->getRepository(USER_ROLE_ENTITY);
		$locationRepository = $this->em->getRepository(LOCATION_ENTITY);
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
		$rentalTypeRepository = $this->em->getRepository(RENTAL_TYPE_ENTITY);
		$emailRepository = $this->em->getRepository(CONTACT_EMAIL_ENTITY);

		$error = new ValidationError;

		$values->country = $locationRepository->find($values->country);
		if(!$values->country || !$values->country->isPrimary()) {
			$error->addError("Invalid country", 'country');
		}

		$values->language = $languageRepository->find($values->language);
		if(!$values->language) {
			$error->addError("Invalid language", 'language');
		}

		// User
		$user = $userRepository->findByLogin($values->email);
		if($user) {
			$error->addError("Email exists", 'email');
		}

		$values->rental->type = $rentalTypeRepository->find($values->rental->type);
		if(!$values->rental->type) {
			$error->addError("Invalid rental type", 'rentalType');
		}

		$error->assertValid();

		/** @var $role \Entity\User\Role */
		$role = $userRoleRepository->findOneBySlug(Role::OWNER);

		/** @var $user \Entity\User\User */
		$user = $userRepository->createNew();
		$user->setRole($role);
		$user->setLogin($values->email);
		$user->setPassword($values->password);
		$user->setPrimaryLocation($values->country);
		$user->setLanguage($values->language);


		/** @var $email \Entity\Contact\Email */
		$email = $emailRepository->createNew();
		$email->setValue($user->getLogin());

		$rentalCreator = $this->rentalCreator;

		/** @var $address \Entity\Contact\Address */
		$address = $this->addressCreator->create($values->rental->address->address);


		/** @var $rental \Entity\Rental\Rental */
		$rental = $rentalCreator->create($address, $user, $values->rental->name);

		$rental->setType($values->rental->type)
			->setEditLanguage($values->language)
			->addSpokenLanguage($values->language)
			->setEmail($email)
			->setClassification($values->rental->classification)
			->setMaxCapacity($values->rental->maxCapacity)
			->setFloatPrice($values->rental->price);

		//$this->model->save($values);
		$this->rental = $rental;

		$this->onSuccess($rental);

		return $rental;
	}

	public function getRental()
	{
		return $this->rental;
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