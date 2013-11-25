<?php
/**
 * This file is part of the Tralandia.
 * User: lukas
 * Created at: 11/7/13 1:29 PM
 */

namespace Tralandia\Harvester;

use Entity\HarvestedContact;
use Image\RentalImageManager;
use Nette\Object;
use Service\Rental\RentalCreator;
use Doctrine\ORM\EntityManager;


class RegistrationData extends Object {


	/**
	 * @var array
	 */
	public $onRegister = [];


	/**
	 * @var array
	 */
	public $onMerge = [];


	/**
	 * @var array
	 */
	public $onSuccess = [];

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

    public function registration($data)
	{
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
			$this->onMerge($rental);
		} else {
			$language = $data['primaryLocation']->defaultLanguage;
			/** @var $rental \Entity\Rental\Rental */
			$rental = $rentalCreator->create($data['address'], $language, $data['name']);
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

			if (isset($data['images'])) {
				foreach ($data['images'] as $path) {
					$image = $this->rm->saveFromFile($path);
					$this->em->persist($rental->addImage($image));
				}
			}

//			is_null($data['description']->answer->getId()) ? : $rental->addInterviewAnswer($data['description']);
			if($data['description']) {
				$rental->getFirstInterviewAnswer()->getAnswer()->setOrCreateTranslationText($language, $data['description']);
			}

			$this->em->persist($rental);
			$this->em->flush();
			$return['success'] = TRUE;
			$return['registered'] = TRUE;
			$this->onRegister($rental);
		}

		foreach($data['phone'] as $phone) {
			$this->harvestedContacts->addIfNotExists($rental, HarvestedContact::TYPE_PHONE, $phone);
		}

		$this->harvestedContacts->addIfNotExists($rental, HarvestedContact::TYPE_EMAIL, $data['email']);



		$return['rental'] = $rental;

		$this->onSuccess($rental);

		return $return;

	}

}
