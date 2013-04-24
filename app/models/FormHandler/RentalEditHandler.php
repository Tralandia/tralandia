<?php

namespace FormHandler;

use Doctrine\ORM\EntityManager;

class RentalEditHandler extends FormHandler
{

	/**
	 * @var array
	 */
	public $onSuccess = [];

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function handleSuccess($values)
	{
		$userRepository = $this->em->getRepository(USER_ENTITY);
		$locationRepository = $this->em->getRepository(LOCATION_ENTITY);
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
		$rentalTypeRepository = $this->em->getRepository(RENTAL_TYPE_ENTITY);
		$amenityRepository = $this->em->getRepository(RENTAL_AMENITY_ENTITY);
		$placementRepository = $this->em->getRepository(RENTAL_PLACEMENT_ENTITY);
		$emailRepository = $this->em->getRepository(CONTACT_EMAIL_ENTITY);

		$rentalValues = $values->rental;

		$error = new ValidationError;

		$values->country = $locationRepository->find($values->country);
		if (!$values->country || !$values->country->isPrimary()) {
			$error->addError("Invalid country", 'country');
		}


		$error->assertValid();

	}

}
