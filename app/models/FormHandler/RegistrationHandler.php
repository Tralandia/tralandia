<?php

namespace FormHandler;

use Service\Rental\RentalCreator;
use Doctrine\ORM\EntityManager;

class RegistrationHandler extends FormHandler
{

	/**
	 * @var \Service\Rental\RentalCreator
	 */
	protected $rentalCreator;

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
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(RentalCreator $rentalCreator, EntityManager $em)
	{
		$this->rentalCreator = $rentalCreator;
		$this->em = $em;
	}

	public function handleSuccess($values)
	{
		$userRepository = $this->em->getRepository('\Entity\User\User');
		$locationRepository = $this->em->getRepository('\Entity\Location\Location');
		$languageRepository = $this->em->getRepository('\Entity\Language');
		$rentalTypeRepository = $this->em->getRepository('\Entity\Rental\Type');
		$referralRepository = $this->em->getRepository('\Entity\Rental\Referral');
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

		/** @var $referral \Entity\Rental\Referral */
		$referral = $referralRepository->createNew();

		/** @var $email \Entity\Contact\Email */
		$email = $emailRepository->createNew();

		/** @var $rental \Entity\Rental\Rental */
		$rentalCreator = $this->rentalCreator;
		$rental = $rentalCreator->create($values->country, $user, $values->rentalName);
		$rentalCreator->setPrice($rental, $values->rentalPrice);

		$rental->setType($values->rentalType)
			->setEditLanguage($values->language)
			->addSpokenLanguage($values->language)
			->addEmail($email)
			->setClassification($values->rentalClassification)
			->setMaxCapacity($values->rentalMaxCapacity)
			->addReferral($referral);

		//$this->model->save($values);
		$this->rental = $rental;
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