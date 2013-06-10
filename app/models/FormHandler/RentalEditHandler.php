<?php

namespace FormHandler;

use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Entity\Rental\Pricelist;
use \Nette\DI\Container;

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

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	public function __construct(Rental $rental, EntityManager $em)
	{
		$this->em = $em;
		$this->rental = $rental;
	}


	public function handleSuccess($values)
	{
//		d($values);
		$values = $values->rental;
		$rental = $this->rental;

		if ($value = $values['address']) {
			$address = $value;
			if ($address['addressEntity']) {
				$rental->address = $address['addressEntity'];
			}
		}

		if ($value = $values['placement']) {
			$placementRepository = $this->em->getRepository(RENTAL_PLACEMENT_ENTITY);
			$placementEntity = $placementRepository->find($value);
			$rental->addPlacement($placementEntity);
		}

		if ($value = $values['type']) {
			$rental->type = $value->type;
			$rental->classification = $value->classification;
		}

		if ($value = $values['photos']) {
			 if (isset($value->sort)) {
				 $rentalImageRepository = $this->em->getRepository(RENTAL_IMAGE_ENTITY);
				 $i=0;
				 foreach(explode(',', $value->sort) as $fileId) {
					$rentalImage = $rentalImageRepository->find($fileId);
					$rentalImage->sort = $i;
					$rentalImage->rental = $this->rental;
					$i++;
				 }
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
//			foreach ($value->list as $priceList) {
//				if ($priceList->entity instanceof Pricelist) {
//					$rental->addPricelist($priceList->entity);
//				}
//			}
		}

		if ($value = $values['phone']) {
			if ($phoneEntity = $values['phone']->entity) {
				$rental->setPhone($phoneEntity);
			}
		}

		if ($value = $values['price']) {
			if ($priceEntity = $values['price']->entity) {
				$rental->setPrice($priceEntity);
			}
		}

		if ($value = $values['interview']) {
			$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
			$answers = $rental->interviewAnswers;
			foreach ($answers as $answer) {
				if (isset($value->{$answer->question->id})) {
					$phrase = $answer->answer;
					foreach ($value[$answer->question->id] as $languageIso => $val) {
						$language = $languageRepository->findByIso($languageIso);
						$translation = $phrase->getTranslation($language[0]);
						if ($translation) {
							$translation->translation = $val;
						} else {
							$phrase->createTranslation($language, $val);
						}
					}
				}
			}
		}

		$rentalInfo = ['name', 'teaser'];
		foreach ($rentalInfo as $infoName) {
			$value = $values[$infoName];
			$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
			$phrase = $rental->{$infoName};
			foreach ($value as $languageIso => $name) {
				$language = $languageRepository->findOneByIso($languageIso);
				$translation = $phrase->getTranslation($language);
				if ($translation) {
					$translation->translation = $name;
				} else if ($name) {
					$phrase->createTranslation($language, $name);
				}
			}
		}

		$amenities = ['board', 'children', 'activity', 'relax', 'service', 'wellness', 'congress', 'kitchen', 'bathroom', 'heating', 'parking', 'rooms', 'other'];
		foreach ($amenities as $amenityName) {
			$value = $values[$amenityName];
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
			$petAmenity = $this->em->getRepository(RENTAL_AMENITY_ENTITY)->findByPetType();

			foreach($petAmenity as $amenity) {
				if ($value && $amenity->id == $value) {
					$rental->addAmenity($amenity);
				} else {
					$rental->removeAmenity($amenity);
				}
			}
		}

		if ($value = $values['separateGroups']) {
			$groupsAmenity = $this->em->getRepository(RENTAL_AMENITY_ENTITY)->findBySeparateGroupsType();
			if ($value===TRUE) {
				$rental->addAmenity($groupsAmenity[0]);
				$rental->removeAmenity($groupsAmenity[1]);
			} else if ($value===FALSE) {
				$rental->removeAmenity($groupsAmenity[0]);
				$rental->addAmenity($groupsAmenity[1]);
			} else if ($value===NULL) {
				$rental->removeAmenity($groupsAmenity[0]);
				$rental->removeAmenity($groupsAmenity[1]);
			}
		}

		if ($value = $values['ownerAvailability']) {
			$availibilityAmenities = $rental->getAmenitiesByType('owner-availability');
			foreach($availibilityAmenities as $amenity) {
				if ($value && $amenity->id==$value) {
					$rental->addAmenity($amenity);
				} else {
					$rental->removeAmenity($amenity);
				}
			}
		}

		if ($value = $values['url']) {
			if (preg_match("/^http:\/\//", $value)==0) {
				$value = 'http://'.$value;
			}
			$rental->setUrl($value);
		}

		if ($value = $values['bedroomCount']) {
			$rental->bedroomCount = $value;
		}

		$simpleValues = ['checkIn', 'checkOut', 'maxCapacity', 'rooms'];
		foreach ($simpleValues as $valueName) {
			if ($value = $values[$valueName]) {
				$rental->{$valueName} = $value;
			}
		}

		$rentalRepository = $this->em->getRepository(RENTAL_ENTITY);
		$rentalRepository->flush();

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

