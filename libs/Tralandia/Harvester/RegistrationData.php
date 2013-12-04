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
use Extras\Books\Phone;
use Image\RentalImageManager;
use Nette\Object;
use Service\Rental\RentalCreator;
use Doctrine\ORM\EntityManager;
use Tralandia\Localization\ITranslatorFactory;
use User\UserCreator;


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
	 * @var UserCreator
	 */
	private $userCreator;

	/**
	 * @var \Tralandia\Localization\ITranslatorFactory
	 */
	private $translatorFactory;


	/**
	 * @param \Service\Rental\RentalCreator $rentalCreator
	 * @param HarvestedContacts $harvestedContacts
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Image\RentalImageManager $rm
	 * @param MergeData $mergeData
	 * @param \User\UserCreator $userCreator
	 * @param \Tralandia\Localization\ITranslatorFactory $translatorFactory
	 *
	 */
    public function __construct(RentalCreator $rentalCreator, HarvestedContacts $harvestedContacts,
								EntityManager $em, RentalImageManager $rm, MergeData $mergeData, UserCreator $userCreator, ITranslatorFactory $translatorFactory)
	{
        $this->rentalCreator = $rentalCreator;
        $this->em = $em;
		$this->rm = $rm;
		$this->harvestedContacts = $harvestedContacts;
		$this->mergeData = $mergeData;
		$this->userCreator = $userCreator;
		$this->translatorFactory = $translatorFactory;
	}

    public function registration($data)
	{
		$return = ['success' => FALSE];
		$rentalCreator = $this->rentalCreator;

		/** @var $mainUser \Entity\User\User */
		$mainUser = $this->em->getRepository(USER_ENTITY)->findOneBy(['login' => $data['email']]);
		if($mainUser && $mainUser->getRentals()) {
			foreach($mainUser->getRentals() as $rental){
				if(!$rental->harvested) {
					$return['success'] = TRUE;
					$return['already_registered'] = TRUE;
					return $return;
				}
			}
		}

		/* Ak sa nachadza dany email alebo cislo v dtb merge-ovanie udajov */
		$rental = $this->harvestedContacts->findRentalByEmail($data['email']);
		if(!$rental && is_array($data['phone'])) {
			foreach($data['phone'] as $phone) {
				$rental = $this->harvestedContacts->findRentalByPhone($phone);
				if($rental) break;
			}
		}

		if($rental){
			$mergeData = $this->mergeData;
			$mergeData->merge($data, $rental);
			$return['success'] = TRUE;
			$return['merged'] = TRUE;
			$this->onMerge($rental);
		} else {

			if($data['email']){
				$language = $data['primaryLocation']->defaultLanguage;

				$environment = new Environment($data['primaryLocation'], $language, $this->translatorFactory);
				/** @var $user \Entity\User\User */
				$user = $this->userCreator->create($data['email'], $environment, Role::OWNER);
				$user->setPassword(\Nette\Utils\Strings::random(10));

				$this->em->persist($user);
			}

			if(isset($user)) {
				$language = $user->getLanguage();
			} else {
				$language = $data['primaryLocation']->defaultLanguage;
			}

			/** @var $rental \Entity\Rental\Rental */
			$rental = $rentalCreator->create($data['address'], $user, $data['name']);

			isset($data['phone'][0]) && ($data['phone'][0] instanceof \Entity\Contact\Phone) && $rental->setPhone($data['phone'][0]);

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
				->setHarvested(TRUE)
				->setFloatPrice($data['price']);

			if (isset($data['images'])) {
				$i = 1;
				foreach ($data['images'] as $path) {
					$imageString = $this->getImageDataSource($path);
					if(!$imageString) continue;
					$image = $this->rm->saveFromString($imageString);
					$this->em->persist($rental->addImage($image));
					if($i == 10) break;
					$i++;
				}
			}

			// @todo zatial to neukladame kvoli SEO
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


	public function getImageDataSource($url, $referrer = NULL){
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$referrer && curl_setopt($ch, CURLOPT_REFERER, $referrer);
		$raw = curl_exec($ch);
		curl_close ($ch);

		return $raw;
	}
}
