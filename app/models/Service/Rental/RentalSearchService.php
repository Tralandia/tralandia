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
	
	public $rentalRepositoryAccessor;

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

	}

	public function inject(\Nette\DI\Container $container) {
		$this->rentalRepositoryAccessor = $container->rentalRepositoryAccessor;
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
				$this->results[$this->primaryLocation->getId()][$criteria][$value->getId()] = $this->findRentalsBy($criteria, $value);
			}
			
		}

	}

	private function findRentalsBy($criteria, $value) {

		switch($criteria) {
			case self::CRITERIA_PRIMARY_LOCATION:

				break;
			case self::CRITERIA_LOCATION:

				break;

			case self::CRITERIA_RENTAL_TYPE:
				return $this->rentalRepositoryAccessor->get()->findByType($value);
				break;

			case self::CRITERIA_AREA_BOUNDRIES:

				break;

			case self::CRITERIA_CAPACITY:

				break;

			case self::CRITERIA_AMENITIES:

				break;

			case self::CRITERIA_LANGUAGES_SPOKEN:

				break;

			case self::CRITERIA_PRICE_CATEGORY:

				break;

			case self::CRITERIA_PRIMARY_LOCATION:
				
				break;

			default:
				return array();
				break;
		}

	}

	public function getResults() {

		return $this->results;

	}

	public function setCountPerPage($num) {}

	public function setPage($num) {}
}