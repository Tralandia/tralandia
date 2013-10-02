<?php

namespace FormHandler;

use Entity\User\Role;
use Environment\Environment;
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
	 * @param \User\UserCreator $userCreator
	 * @param \Environment\Environment $environment
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(RentalCreator $rentalCreator,
								UserCreator $userCreator, Environment $environment, EntityManager $em)
	{
		$this->rentalCreator = $rentalCreator;
		$this->userCreator = $userCreator;
		$this->environment = $environment;
		$this->em = $em;
	}


	public function handleSuccess($values)
	{
		$userRepository = $this->em->getRepository(USER_ENTITY);
		$locationRepository = $this->em->getRepository(LOCATION_ENTITY);
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
		$amenityRepository = $this->em->getRepository(RENTAL_AMENITY_ENTITY);
		$placementRepository = $this->em->getRepository(RENTAL_PLACEMENT_ENTITY);

		$rentalValues = $values->rental;

		$error = new ValidationError;

		$values->country = $locationRepository->find($values->country);
		if (!$values->country || !$values->country->isPrimary()) {
			$error->addError("Invalid country", 'country');
		}

		$values->language = $languageRepository->find($values->language);
		if (!$values->language) {
			$error->addError("Invalid language", 'language');
		}

		// User
		$user = $userRepository->findOneByLogin($values->email);
		if ($user) {
			$error->addError('o2610', 'email');
		}

		if (!$rentalValues->type->type) {
			$error->addError("Invalid rental type", 'rentalType');
		}

		$error->assertValid();

		$user = $this->userCreator->create($values->email, $this->environment, Role::OWNER);
		$user->setPassword($values->password);

		$this->em->persist($user);

		$rentalCreator = $this->rentalCreator;

		/** @var $address \Entity\Contact\Address */
		$address = $rentalValues->address->addressEntity;


		/** @var $rental \Entity\Rental\Rental */
		$rental = $rentalCreator->create($address, $user, $rentalValues->name);

		$rental->setType($rentalValues->type->type)
			->setEditLanguage($values->language)
			->addSpokenLanguage($values->language)
			->setEmail($values->email)
			->setClassification($rentalValues->type->classification)
			->setMaxCapacity($rentalValues->maxCapacity)
			->setCheckIn($rentalValues->checkIn)
			->setCheckOut($rentalValues->checkOut)
			->setFloatPrice($rentalValues->price);

		if($rentalValues->phone->entity) $rental->setPhone($rentalValues->phone->entity);

		if($values->url) $rental->setUrl($values->url);

		$board = is_object($rentalValues->board) ? ((array)$rentalValues->board->getIterator()) : $rentalValues->board;
		$important = is_object($rentalValues->important) ? ((array)$rentalValues->important->getIterator()) : $rentalValues->important;
		$amenityIds = array_merge($important, $board, [$rentalValues->ownerAvailability, $rentalValues->pet]);
		$amenities = $amenityRepository->findById($amenityIds);
		foreach ($amenities as $amenity) {
			$rental->addAmenity($amenity);
		}

		$placements = $placementRepository->findById($rentalValues->placement);
		foreach ($placements as $placement) {
			$rental->addPlacement($placement);
		}

		if (isset($rentalValues->photos)) {
			foreach ($rentalValues->photos->images as $image) {
				$rental->addImage($image);
			}
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
