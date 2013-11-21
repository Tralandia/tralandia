<?php
/**
 * This file is part of the Tralandia.
 * User: lukas
 * Created at: 11/7/13 1:29 PM
 */

namespace Tralandia\Harvester;

use Entity\User\Role;
use Environment\Environment;
use Image\RentalImageManager;
use Service\Rental\RentalCreator;
use Doctrine\ORM\EntityManager;
use User\UserCreator;
use Nette\Utils\Strings;


class RegistrationData {

    /**
     * @var \Service\Rental\RentalCreator
     */
    protected $rentalCreator;

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
	 * @var \Image\RentalImageManager
	 */
	private $rm;

    /**
    * @param \Service\Rental\RentalCreator $rentalCreator
    * @param \Environment\Environment $environment
    * @param \Doctrine\ORM\EntityManager $em
	* @param \Image\RentalImageManager $rm
    */
    public function __construct(RentalCreator $rentalCreator, Environment $environment,
								EntityManager $em, RentalImageManager $rm)
    {
        $this->rentalCreator = $rentalCreator;
        $this->environment = $environment;
        $this->em = $em;
		$this->rm = $rm;
    }

    public function registration($data){
		$userRepository = $this->em->getRepository(USER_ENTITY);
		$rentalRepository = $this->em->getRepository(RENTAL_ENTITY);
        $locationRepository = $this->em->getRepository(LOCATION_ENTITY);
        $languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
		$imageDao = $this->em->getRepository(RENTAL_IMAGE_ENTITY);

		/* Bude nova tabulka s kontaktmi(email, tel. c.), kde sa to bude kontrolovat */

		$rentalCreator = $this->rentalCreator;

		/** @var $rental \Entity\Rental\Rental */
		$rental = $rentalCreator->create($data['address'], $data['primaryLocation']->iso, $data['name']);

		/* Ak sa nachadza dany email alebo cislo v dtb merge-ovanie udajov */
		$rentalObjectMail = is_null($data['email']) ? NULL : $rentalRepository->findOneBy(['email' => $data['email']]);
		$rentalObjectPhone = is_null($data['phone']) ? NULL : $rentalRepository->findOneBy(['phone' => $data['phone']]);
		$rentalObject = isset($rentalObjectMail) ? $rentalObjectMail : $rentalObjectPhone;
		if($rentalObject){
			$mergeData = new MergeData($this->em);
			$mergeData->merge($data, $rentalObject);
		} else {
			is_null($data['phone']) ? : $rental->setPhone($data['phone']);
			is_null($data['contactName']) ? : $rental->setContactName($data['contactName']);
			is_null($data['url']) ? : $rental->setUrl($data['url']);
			is_null($data['bedroomCount']) ? : $rental->setBedroomCount($data['bedroomCount']);

			$rental->setType($data['type'])
				->setEditLanguage($data['editLanguage'])
				->addSpokenLanguage($data['spokenLanguage'])
//				->setContactName($data['contactName'])
//				->setBedroomCount($data['bedroomCount'])
				->setEmail($data['email'])
				->setClassification($data['classification'])
				->setMaxCapacity($data['maxCapacity'])
				->setCheckIn($data['checkIn'])
				->setCheckOut($data['checkOut'])
				->setFloatPrice($data['price']);

			if (isset($data['images'])) {
				foreach ($data['images'] as $path) {
					$image = $this->rm->saveFromFile($path);
					$this->em->persist($rental->addImage($image));
//					$a = $rental->addImage($image);
				}
			}

			is_null($data['description']->answer->getId()) ? : $rental->addInterviewAnswer($data['description']);
			$this->em->persist($rental);
			$this->em->flush();

			return $rental;
		}

    }

}
