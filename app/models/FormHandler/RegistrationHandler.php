<?php

namespace FormHandler;

use Entity\User\Role;
use Environment\Environment;
use Service\Contact\AddressCreator;
use Service\Rental\RentalCreator;
use Doctrine\ORM\EntityManager;
use User\UserCreator;

class RegistrationHandler extends FormHandler
{

	/**
	 * @var array
	 */
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
	 * @var \User\UserCreator
	 */
	protected $userCreator;

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

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
	 * @param \User\UserCreator $userCreator
	 * @param \Environment\Environment $environment
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(RentalCreator $rentalCreator, AddressCreator $addressCreator,
								UserCreator $userCreator, Environment $environment, EntityManager $em)
	{
		$this->rentalCreator = $rentalCreator;
		$this->addressCreator = $addressCreator;
		$this->userCreator = $userCreator;
		$this->environment = $environment;
		$this->em = $em;
	}

	public function handleSuccess($values)
	{
		$userRepository = $this->em->getRepository(USER_ENTITY);
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

		$values->rental->type->type = $rentalTypeRepository->find($values->rental->type->type);
		if(!$values->rental->type->type) {
			$error->addError("Invalid rental type", 'rentalType');
		}

		$error->assertValid();

		$user = $this->userCreator->create($values->email, $this->environment, Role::OWNER);
		$user->setPassword($values->password);


		/** @var $email \Entity\Contact\Email */
		$email = $emailRepository->createNew();
		$email->setValue($user->getLogin());

		$rentalCreator = $this->rentalCreator;

		/** @var $address \Entity\Contact\Address */
		$address = $this->addressCreator->create($values->rental->address->address);


		/** @var $rental \Entity\Rental\Rental */
		$rental = $rentalCreator->create($address, $user, $values->rental->name);

		$rental->setType($values->rental->type->type)
			->setEditLanguage($values->language)
			->addSpokenLanguage($values->language)
			->setEmail($email)
			->setClassification($values->rental->type->classification)
			->setMaxCapacity($values->rental->maxCapacity)
			->setFloatPrice($values->rental->price);

		foreach($values->rental->photos->images as $image) {
			$rental->addImage($image);
		}


		$this->rental = $rental;

		$this->em->persist($rental);
		$this->em->flush();

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
