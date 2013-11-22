<?php
/**
 * This file is part of the Tralandia.
 * User: lukas
 * Created at: 11/7/13 1:29 PM
 */

namespace Tralandia\Harvester;

use Entity\HarvestedContact;
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
	 * @var HarvestedContacts
	 */
	private $harvestedContacts;

	/**
	 * @var MergeData
	 */
	private $mergeData;


	/**
	 * @param \Service\Rental\RentalCreator $rentalCreator
	 * @param HarvestedContacts $harvestedContacts
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Image\RentalImageManager $rm
	 * @param MergeData $mergeData
	 */
    public function __construct(RentalCreator $rentalCreator, HarvestedContacts $harvestedContacts,
								EntityManager $em, RentalImageManager $rm, MergeData $mergeData)
    {
        $this->rentalCreator = $rentalCreator;
        $this->em = $em;
		$this->rm = $rm;
		$this->harvestedContacts = $harvestedContacts;
		$this->mergeData = $mergeData;
	}

    public function registration($data){
		$userRepository = $this->em->getRepository(USER_ENTITY);
		$rentalRepository = $this->em->getRepository(RENTAL_ENTITY);
        $locationRepository = $this->em->getRepository(LOCATION_ENTITY);
        $languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
		$imageDao = $this->em->getRepository(RENTAL_IMAGE_ENTITY);

		/* Bude nova tabulka s kontaktmi(email, tel. c.), kde sa to bude kontrolovat */

		$rentalCreator = $this->rentalCreator;

		/* Ak sa nachadza dany email alebo cislo v dtb merge-ovanie udajov */
		$rental = $this->harvestedContacts->findRentalByEmail($data['email']);
		if(!$rental && is_array($data['phone'])) {
			foreach($data['phone'] as $phone) {
				$rental = $this->harvestedContacts->findRentalByPhone($phone);
				if($rental) break;
			}
		}

		$return = ['success' => FALSE];
		if($rental){
			$mergeData = $this->mergeData;
			$mergeData->merge($data, $rental);
			$return['success'] = TRUE;
			$return['merged'] = TRUE;
		} else {
			/** @var $rental \Entity\Rental\Rental */
			$rental = $rentalCreator->create($data['address'], $data['primaryLocation']->iso, $data['name']);
			$a = $data['phone'];
			empty($data['phone']) ? : $rental->setPhone($data['phone'][0]);
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

//			if (isset($data['images'])) {
//				foreach ($data['images'] as $path) {
//					$image = $this->rm->saveFromFile($path);
//					$this->em->persist($rental->addImage($image));
//				}
//			}

			is_null($data['description']->answer->getId()) ? : $rental->addInterviewAnswer($data['description']);

			$this->em->persist($rental);
			$this->em->flush();
			$return['success'] = TRUE;
			$return['registered'] = TRUE;

		}

		foreach($data['phone'] as $phone) {
			$this->harvestedContacts->addIfNotExists($rental, HarvestedContact::TYPE_PHONE, $phone);
		}

		$this->harvestedContacts->addIfNotExists($rental, HarvestedContact::TYPE_EMAIL, $data['email']);



		$return['rental'] = $rental;

		return $return;

	}

}
