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


	public function handleSuccess($values)
	{
		$rental = $this->rental;

		foreach ($values as $key => $value) {
			if ($value instanceof \Nette\ArrayHash) {

				if ($key=='address') {
					$address = $value;
					if ($address['addressEntity']) {
						$rental->address = $address['addressEntity'];
					}
					continue;
				}

				if ($key=='placement') {
					$rental->placement = $value;
					continue;
				}

				if ($key=='type') {
					$rental->type = $value->type;
					$rental->classification = $value->classification;
					continue;
				}

				if ($key=='photos') {
					// @TODO: nemame dorieseny upload obrazkov
					continue;
				}

				if ($key=='priceList') {
					$pricelistRows = $rental->getPricelistRows();
					foreach ($pricelistRows as $pricelistRow) {
						$rental->removePricelistRow($pricelistRow);
					}
					foreach ($value->list as $pricelistRow) {
						if ($pricelistRow->entity) $rental->addPricelistRow($pricelistRow->entity);
					}
					continue;
				}

				if ($key=='priceUpload') {
					$pricelists = $rental->getPricelists();
					foreach ($pricelists as $pricelist) {
						$rental->removePricelist($pricelist);
					}
					foreach ($value->list as $pricelist) {
						if ($pricelist->entity) $rental->addPricelist($pricelist->entity);
					}
					continue;
				}

				if ($key=='phone' && $value->entity) {
					$rental->setPhone($value->entity);
					continue;
				}

				if ($key=='price' && $priceEntity = $value->entity) {
					$rental->setPrice($priceEntity);
					continue;
				}

				if ($key=='name' || $key=='teaser') {
					$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
					$phrase = $rental->{$key};
					foreach ($value as $languageIso => $name) {
						$language = $languageRepository->findByIso($languageIso);
						$translation = $phrase->getTranslation($language[0]);
						if ($translation) {
							$translation->translation = $name;
						} else if ($name) {
							$phrase->createTranslation($language, $name);
						}
					}
					continue;
				}

				if ($key=='interview') {
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
					continue;
				}

				$amenities = ['board', 'children', 'activity', 'relax', 'service', 'wellness', 'congress', 'kitchen', 'bathroom', 'heating', 'parking', 'room', 'other'];
				if (in_array($key, $amenities)) {
					$amenities = $rental->getAmenitiesByType($key);
					foreach ($amenities as $amenity) {
						$rental->removeAmenity($amenity);
					}
					foreach ($value as $amenity) {
						$rental->addAmenity($amenity);
					}
					continue;
				}

			} else if ($key=='pet') {
				$petAmenity = $this->em->getRepository(RENTAL_AMENITY_ENTITY)->findByPetType();

				foreach($petAmenity as $amenity) { 
					if ($value && $amenity->id == $value->id) {
						$rental->addAmenity($amenity);
					} else {
						$rental->removeAmenity($amenity);
					}
				}
				continue;
			} else if ($key=='separateGroups') {
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
				continue;
			} else if ($key=='ownerAvailability') {
				$availibilityAmenities = $rental->getAmenitiesByType('owner-availability');
				foreach($availibilityAmenities as $amenity) {
					if ($value && $value->id==$amenity->id) {
						$rental->addAmenity($amenity);
					} else {
						$rental->removeAmenity($amenity);
					}
				}
				continue;
			} else if ($key=='url') {
				if (preg_match("/^http:\/\//", $value)==0) {
					$value = 'http://'.$value;
				}
				$rental->setUrl($value);
				continue;
			} else if ($value) {
				$rental->{$key} = $value;
				continue;
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

