<?php

namespace Service\Rental;

use Service, Doctrine, Entity, Nette;
use Extras\Cache\IRentalSearchCachingFactory;


class RentalSearchService extends Nette\Object 
{

	const COUNT_PER_PAGE			= 50;
	const CRITERIA_LOCATION  		= 'location';
	const CRITERIA_RENTAL_TYPE 		= 'rentalType';
	const CRITERIA_TAG	 			= 'tag';
	const CRITERIA_CAPACITY 		= 'capacity';
	const CRITERIA_SPOKEN_LANGUAGE 	= 'spokenLanguage';
	const CRITERIA_PRICE 			= 'price';
	//const CRITERIA_AREA_BOUNDRY 	= 'areaBoundry';

	const CAPACITY_MAX				= 50;

	protected $primaryLocation;
	protected $criteria = array();

	protected $results;

	protected $rentalSearchCaching;
	protected $rentalRepositoryAccessor;

	public function __construct(\Entity\Location\Location $primaryLocation, IRentalSearchCachingFactory $rentalSearchCachingFactory) {
		$this->primaryLocation = $primaryLocation;

		$this->rentalSearchCaching = $rentalSearchCachingFactory->create($this->primaryLocation);
	}

	public function inject(\Nette\DI\Container $container) {
		$this->rentalRepositoryAccessor = $container->rentalRepositoryAccessor;
	}

	// Criteria
	// ==============================
	public function addLocationCriteria(Entity\Location\Location $location) {
		$this->criteria[self::CRITERIA_LOCATION] = $location;
	}

	public function addRentalTypeCriteria(Entity\Rental\Type $rentalType) {
		$this->criteria[self::CRITERIA_RENTAL_TYPE] = $rentalType;
	}

	public function addRentalTagCriteria(Entity\Rental\Tag $tag) {
		$this->criteria[self::CRITERIA_TAG] = $tag;
	}

	public function addCapacityCriteria($capacity) {
		$this->criteria[self::CRITERIA_CAPACITY] = $capacity;
	}

	public function addSpokenLanguageCriteria(Entity\Language $spokenLanguage) {
		$this->criteria[self::CRITERIA_SPOKEN_LANGUAGE] = $spokenLanguage;
	}

	public function addPriceCriteria($price) {
		$this->criteria[self::CRITERIA_PRICE] = $price;
	}

	public function getRentalIds($page = NULL) {
		$results = $this->getResults();

		if ($page === NULL) {
			return $results;
		} else {
			$results = array_chunk($results, self::COUNT_PER_PAGE);
			return isset($results[$page]) ? $results[$page] : NULL;
		}
	}

	public function getRentals($page = NULL) {
		$results = $this->getRentalIds($page);

		return $this->rentalRepositoryAccessor->get()->findById($results);
	}

	public function getRentalsCount() {
		return count($this->getResults());
	}

	//=================================

	protected function getResults() {
		if ($this->results !== NULL) {
			return $this->results;
		}

		$cache = array();

		foreach ($this->criteria as $key => $value) {
			$cache[$key] = $this->rentalSearchCaching->load($key.(is_object($value) ? $value->id : $value));
		}
		$cache = array_filter($cache);

		if (count($cache) > 1) {
			$tempResults = call_user_func_array('array_intersect', $cache);			
		} else {
			$tempResults = reset($cache);
		}

		if(is_array($tempResults)) {
			$this->results = $this->reorderResults($tempResults);
		} else {
			$this->results = array();
		}

		return $this->results;
	}

	protected function reorderResults(array $results) {
		$order = $this->rentalSearchCaching->getOrderList();
		$t = array();

		foreach ($results as $key => $value) {
			$t[$key] = $order[$key];
		}
		asort($t);

		return array_keys($t);
	}
}
