<?php

namespace FormHandler;

use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Entity\Rental\Pricelist;
use \Nette\DI\Container;
use Tralandia\Amenity\Amenities;
use Tralandia\Dictionary\PhraseManager;
use Tralandia\Language\Languages;

class RentalEditHandler extends FormHandler
{

	/**
	 * @var array
	 */
	public $onSuccess = [];
	public $onGpsChange = [];


	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Tralandia\Dictionary\PhraseManager
	 */
	private $phraseManager;

	/**
	 * @var \Tralandia\Amenity\Amenities
	 */
	private $amenities;

	/**
	 * @var \Tralandia\Language\Languages
	 */
	private $languages;


	public function __construct(Rental $rental, PhraseManager $phraseManager,
								Amenities $amenities, Languages $languages,
								EntityManager $em)
	{
		$this->em = $em;
		$this->rental = $rental;
		$this->phraseManager = $phraseManager;
		$this->amenities = $amenities;
		$this->languages = $languages;
	}


	public function handleSuccess($values)
	{
		$values = $values->rental;
		$rental = $this->rental;


		if($value = $values['spokenLanguages']) {
			$spokenLanguages = $this->languages->findByIds($value);
			$rental->setSpokenLanguages($spokenLanguages);
		}

		if ($value = $values['address']) {
			$address = $value;
			if ($address['addressEntity']) {
				/** @var $newGps \Extras\Types\Latlong */
				$newGps = $address['addressEntity']->getGps();
				$oldGps = $rental->getAddress()->getGps();

				$rental->address = $address['addressEntity'];
				if($oldGps->getLatitude() != $newGps->getLatitude() || $oldGps->getLongitude() != $newGps->getLongitude()) {
					$gpsIsChanged = TRUE;
				}
			}
		}

		if ($value = $values['placement']) {
			$placementRepository = $this->em->getRepository(RENTAL_PLACEMENT_ENTITY);
			$placementEntity = $placementRepository->find($value);
			$rental->setPlacement($placementEntity);
		}

		if ($value = $values['type']) {
			$rental->type = $value->type;
			$rental->classification = $value->classification;
		}

		if ($value = $values['photos']) {
			$i = 0;
			/** @var $imageEntity \Entity\Rental\Image */
			foreach ($value->images as $imageEntity) {
				$imageEntity->setSort($i);
				$this->rental->addImage($imageEntity);
				$i++;
			}
		}

		if ($value = $values['priceList']) {
			$pricelistRows = $rental->getPricelistRows();
			foreach ($pricelistRows as $pricelistRow) {
				$rental->removePricelistRow($pricelistRow);
			}
			foreach ($value->list as $pricelistRow) {
				if ($pricelistRow->entity) {
					$rental->addPricelistRow($pricelistRow->entity);
				}
			}
		}

		if ($value = $values['priceUpload']) {
			$priceLists = $rental->getPricelists();
			foreach ($priceLists as $priceList) {
				$rental->removePricelist($priceList);
			}
			foreach ($value->list as $priceList) {
				if ($priceList->entity instanceof Pricelist) {
					$rental->addPricelist($priceList->entity);
				}
			}
		}

		if ($value = $values['phone']) {
			if ($phoneEntity = $values['phone']->entity) {
				$rental->setPhone($phoneEntity);
			}
		}

		if ($value = $values['price']) {
			$rental->setFloatPrice($value);
		}

		if ($value = $values['interview']) {
			$answers = $rental->getInterviewAnswers();
			foreach ($answers as $answer) {
				if (isset($value->{$answer->getQuestion()->getId()})) {
					$phrase = $answer->getAnswer();
					$translationsVariations = [];
					foreach ($value[$answer->getQuestion()->getId()] as $languageIso => $val) {
						$translationsVariations[$languageIso] = $val;
					}
					$this->phraseManager->updateTranslations($phrase, $translationsVariations);
				}
			}
		}

		$rentalInfo = ['name', 'teaser'];
		foreach ($rentalInfo as $infoName) {
			$value = $values[$infoName];
			$phrase = $rental->{$infoName};
			$translationsVariations = [];
			foreach ($value as $languageIso => $val) {
				$translationsVariations[$languageIso] = $val;
			}
			$this->phraseManager->updateTranslations($phrase, $translationsVariations);
		}

		$amenities = ['board', 'children', 'service', 'wellness', 'kitchen', 'bathroom', 'nearBy' => 'near-by', 'rentalServices' => 'rental-services', 'onFacility' => 'on-premises', 'sportsFun' => 'sports-fun'];
		foreach ($amenities as $valueName => $amenityName) {
			if(is_numeric($valueName)) $valueName = $amenityName;
			$value = $values[$valueName];
			$amenities = $rental->getAmenitiesByType($amenityName);
			foreach ($amenities as $amenity) {
				$rental->removeAmenity($amenity);
			}

			$amenityRepository = $this->em->getRepository(RENTAL_AMENITY_ENTITY);
			foreach ($value as $amenityId) {
				$amenityEntity = $amenityRepository->find($amenityId);
				$rental->addAmenity($amenityEntity);
			}
		}

		if ($value = $values['pet']) {
			$petAmenity = $this->amenities->findByPetType();

			foreach ($petAmenity as $amenity) {
				if ($value && $amenity->id == $value) {
					$rental->addAmenity($amenity);
				} else {
					$rental->removeAmenity($amenity);
				}
			}
		}

		if ($value = $values['ownerAvailability']) {
			$availabilityAmenities = $this->amenities->findByOwnerAvailabilityType();
			foreach ($availabilityAmenities as $amenity) {
				if ($amenity->getId() == $value) {
					$rental->addAmenity($amenity);
				} else {
					$rental->removeAmenity($amenity);
				}
			}
		}

		$value = $values['url'];
		$rental->setUrl($value);

		if ($value = $values['bedroomCount']) {
			$rental->bedroomCount = $value;
		}

		$simpleValues = ['checkIn', 'checkOut', 'maxCapacity', 'contactName', 'email'];
		foreach ($simpleValues as $valueName) {
			if ($value = $values[$valueName]) {
				$rental->{$valueName} = $value;
			}
		}

		$rental->rooms = $values['roomsLayout'];

		$this->em->persist($rental);
		$this->em->flush();

		if(isset($gpsIsChanged)) {
			$this->onGpsChange($rental);
		}
		$this->onSuccess($rental);

		return $rental;
	}
}


interface IRentalEditHandlerFactory
{

	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return RentalEditHandler
	 */
	public function create(Rental $rental);
}

