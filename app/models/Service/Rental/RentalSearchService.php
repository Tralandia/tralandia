<?php

namespace Service\Rental;

use Service, Doctrine, Entity;

/**
 * @author Jan Czibula
 */
class RentalSearchService extends Service\BaseService 
{

	public $primaryLocation;
	public $cacheFactory;
	public $searchCache;
	
	public $rentalRepositoryAccessor;
	public $locationRepositoryAccessor;

	public $results;

	const CRITERIA_PRIMARY_LOCATION = 'primaryLocation';
	const CRITERIA_LOCATION  		= 'location';
	const CRITERIA_RENTAL_TYPE 		= 'rentalType';
	const CRITERIA_AREA_BOUNDRIES 	= 'areaBoundries';
	const CRITERIA_CAPACITY 		= 'capacity';
	const CRITERIA_AMENITIES 		= 'amenities';
	const CRITERIA_LANGUAGES_SPOKEN = 'languagesSpoken';
	const CRITERIA_PRICE_CATEGORY 	= 'priceCategory';

	public function __construct(\Entity\Location\Location $location) {

		$this->addCriteria(self::CRITERIA_PRIMARY_LOCATION, $location);
		$this->searchCache = $this->cacheFactory->create($this->primaryLocation);

	}

	public function inject(\Nette\DI\Container $container) {
		$this->rentalRepositoryAccessor = $container->rentalRepositoryAccessor;
		$this->locationRepositoryAccessor = $container->locationRepositoryAccessor;
	}

	public function injectCache(\Extras\Cache\ISearchCachingFactory $cache) {
		$this->cacheFactory = $cache;
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
					$this->results[$this->primaryLocation->getId()][$criteria][$value->getId()] = $rentalsIds;
				}
			}
			
		}

	}

	public function getResults() {
		return $this->results;
	}

	public function setCountPerPage($num) {}

	public function setPage($num) {}
}