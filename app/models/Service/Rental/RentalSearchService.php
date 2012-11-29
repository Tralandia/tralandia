<?php

namespace Service\Rental;

use Service, Doctrine, Entity;

/**
 * @author Jan Czibula
 */
class RentalSearchService extends Service\BaseService 
{

	const CRITERIA_PRIMARY_LOCATION = 'primaryLocation';
	const CRITERIA_LOCATION  		= 'location';
	const CRITERIA_RENTAL_TYPE 		= 'rentalType';
	const CRITERIA_AREA_BOUNDRIES 	= 'areaBoundries';
	const CRITERIA_CAPACITY 		= 'capacity';
	const CRITERIA_AMENITIES 		= 'amenities';
	const CRITERIA_LANGUAGES_SPOKEN = 'languagesSpoken';
	const CRITERIA_PRICE_CATEGORY 	= 'priceCategory';

	public $primaryLocation;
	public $cacheFactory;
	public $searchCache;
	
	public $rentalRepositoryAccessor;
	public $locationRepositoryAccessor;

	public $results;

	public function __construct(\Entity\Location\Location $location) {

		$this->addCriteria(self::CRITERIA_PRIMARY_LOCATION, $location);

	}

	public function inject(\Nette\DI\Container $container) {
		$this->rentalRepositoryAccessor = $container->rentalRepositoryAccessor;
		$this->locationRepositoryAccessor = $container->locationRepositoryAccessor;
	}

	public function injectCache(\Extras\Cache\ISearchCachingFactory $cache) {
		$this->cacheFactory = $cache;
		$this->searchCache = $this->cacheFactory->create($this->primaryLocation);
	}

	public function addCriteria($criteria, $values) {
		if (!is_array($values)) $values = array($values);

		foreach ($values as $key => $value) {

			if ($criteria===self::CRITERIA_PRIMARY_LOCATION) {
				$this->primaryLocation = $value;
			} else {
				$this->searchCache->setCriteria($criteria);
				$this->searchCache->setValue($value);

				if ($rentalsIds = $this->searchCache->findRentalIdsBy($criteria, $value)) {
					$this->results[$this->primaryLocation->getId()][$criteria][(is_object($value) ? $value->getId() : $value)] = $rentalsIds;
				}
			}
			
		}

	}

	public function getRentalIds() {

		foreach ($this->results[$this->primaryLocation->getId()] as $criteria => $result) {

			$criteriaIds = array();
			foreach ($result as $key => $value) {
				$criteriaIds = array_merge($criteriaIds, $value);
			}

			if (isset($return)) {
				$return = array_intersect($return, $criteriaIds);
			} else {
				$return = $criteriaIds;
			}

		}

		return $return;

	}

	public function getRentals() {

		$rentals = array();

		foreach ($this->getRentalIds() as $rentalId) {
			$rentals[] = $this->rentalRepositoryAccessor->get()->findOneById($rentalId);
		}

		return $rentals;

	}

	public function setCountPerPage($num) {}

	public function setPage($num) {}
}