<?php

namespace FormHandler;

use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
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


	public function handleSuccess($form)
	{
		$values = $form->getValues()->rental;
		$rental = $this->rental;

		if ($value = $values['address']) {
			$address = $value;
			if ($address['addressEntity']) {
				$rental->address = $address['addressEntity'];
			}
		}

		if ($value = $values['placement']) {
			$rental->placement = $value;
		}

		if ($value = $values['type']) {
			$rental->type = $value->type;
			$rental->classification = $value->classification;
		}

		if ($value = $values['photos']) {
			// @TODO: nemame dorieseny upload obrazkov
		}

		if ($value = $values['priceList']) {
			$pricelistRows = $rental->getPricelistRows();
			foreach ($pricelistRows as $pricelistRow) {
				$rental->removePricelistRow($pricelistRow);
			}
			foreach ($value->list as $pricelistRow) {
				if ($pricelistRow->entity) $rental->addPricelistRow($pricelistRow->entity);
			}
		}

		if ($value = $values['priceUpload']) {
			$pricelists = $rental->getPricelists();
			foreach ($pricelists as $pricelist) {
				$rental->removePricelist($pricelist);
			}
			foreach ($value->list as $pricelist) {
				if ($pricelist->entity) $rental->addPricelist($pricelist->entity);
			}
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

		$amenities = ['board', 'children', 'activity', 'relax', 'service', 'wellness', 'congress', 'kitchen', 'bathroom', 'heating', 'parking', 'room', 'other'];
		foreach ($amenities as $amenityName) {
			$value = $values[$amenityName];
			$amenities = $rental->getAmenitiesByType($amenityName);
			foreach ($amenities as $amenity) {
				$rental->removeAmenity($amenity);
			}
			foreach ($value as $amenity) {
				$rental->addAmenity($amenity);
			}
		}

		if ($value = $values['pet']) {
			$petAmenity = $this->em->getRepository(RENTAL_AMENITY_ENTITY)->findByPetType();

			foreach($petAmenity as $amenity) {
				if ($value && $amenity->id == $value->id) {
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
				if ($value && $value->id==$amenity->id) {
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

		$simpleValues = ['checkIn', 'checkOut', 'maxCapacity', 'bedroomCount', 'rooms'];
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

