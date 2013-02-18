<?php

namespace FormHandler;

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
		$userRepository = $this->em->getRepository('\Entity\User\User');
		$locationRepository = $this->em->getRepository('\Entity\Location\Location');
		$languageRepository = $this->em->getRepository('\Entity\Language');
		$rentalTypeRepository = $this->em->getRepository('\Entity\Rental\Type');
		$emailRepository = $this->em->getRepository('\Entity\Contact\Email');

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

		$values->rentalType = $rentalTypeRepository->find($values->rentalType);
		if(!$values->rentalType) {
			$error->addError("Invalid rental type", 'rentalType');
		}

		$error->assertValid();

		/** @var $user \Entity\User\User */
		$user = $userRepository->createNew();
		$user->setLogin($values->email);
		$user->setPassword($values->password);
		$user->setLanguage($values->language);


		/** @var $email \Entity\Contact\Email */
		$email = $emailRepository->createNew();
		$email->setValue($user->getLogin());

		$rentalCreator = $this->rentalCreator;

		$rentalAddress = $values->rentalAddress;

		/** @var $address \Entity\Contact\Address */
		$address = $this->addressCreator->create(
			$values->country,
			$rentalAddress->address,
			$rentalAddress->locality,
			$rentalAddress->postalCode,
			$rentalAddress->latitude,
			$rentalAddress->longitude
		);


		/** @var $rental \Entity\Rental\Rental */
		$rental = $rentalCreator->create($address, $user, $values->rentalName);

		$rental->setType($values->rentalType)
			->setEditLanguage($values->language)
			->addSpokenLanguage($values->language)
			->addEmail($email)
			->setClassification($values->rentalClassification)
			->setMaxCapacity($values->rentalMaxCapacity)
			->setFloatPrice($values->rentalPrice);

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