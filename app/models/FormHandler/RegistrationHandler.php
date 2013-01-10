<?php

namespace FormHandler;

use Repository\Rental\RentalRepository;
use Service\Rental\RentalCreator;

class RegistrationHandler extends FormHandler
{

	public $onInvoiceExists = array();
	public $onInvoiceNotExists = array();

	/**
	 * @var \Repository\Rental\RentalRepository
	 */
	protected $rentalRepository;

	/**
	 * @var \Service\Rental\RentalCreator
	 */
	protected $rentalCreator;

	/**
	 * @param \Repository\Rental\RentalRepository $rentalRepository
	 * @param \Service\Rental\RentalCreator $rentalCreator
	 */
	public function __construct(RentalRepository $rentalRepository, RentalCreator $rentalCreator)
	{
		$this->rentalRepository = $rentalRepository;
		$this->rentalCreator = $rentalCreator;
	}

	public function handleSuccess($values)
	{
		$userRepository = $this->rentalRepository->related('user');
		$locationRepository = $this->rentalRepository->related('primaryLocation');
		$languageRepository = $this->rentalRepository->related('editLanguage');
		$rentalTypeRepository = $this->rentalRepository->related('type');
		$referralRepository = $this->rentalRepository->related('referrals');
		$emailRepository = $this->rentalRepository->related('emails');

		$error = new ValidationError;

		/** @var $location \Entity\Location\Location */
		$location = $locationRepository->find($values->location);
		if(!$location || !$location->isPrimary()) {
			$error->addError("Invalid location", 'location');
		}

		/** @var $language \Entity\Language */
		$language = $languageRepository->find($values->language);
		if(!$language) {
			$error->addError("Invalid language", 'language');
		}

		// User
		$user = $userRepository->findByEmail($values->email);
		if($user) {
			$error->addError("Email exists", 'email');
		}

		/** @var $rentalType \Entity\Rental\Type */
		$rentalType = $rentalTypeRepository->find($values->rentalType);
		if(!$rentalType) {
			$error->addError("Invalid rental type", 'rentalType');
		}

		$error->assertValid();

		/** @var $user \Entity\User\User */
		$user = $this->userRepository->createNew();
		$user->setLogin($values->login);
		$user->setPassword($values->password);
		$user->setLanguage($language);

		/** @var $referral \Entity\Rental\Referral */
		$referral = $referralRepository->createNew();

		/** @var $email \Entity\Contact\Email */
		$email = $emailRepository->createNew();

		/** @var $rental \Entity\Rental\Rental */
		$rentalCreator = $this->rentalCreator;
		$rental = $rentalCreator->create($location, $user, $values->rentalName);
		$rentalCreator->setPrice($rental, $values->rentalPrice);

		$rental->setType($rentalType)
			->setEditLanguage($language)
			->addSpokenLanguage($language)
			->addEmail($email)
			->setClassification($values->rentalClassification)
			->setMaxCapacity($values->rentalMaxCapacity)
			->addReferral($referral);


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