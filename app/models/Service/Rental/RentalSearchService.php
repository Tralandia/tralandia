<?php

namespace Service\Rental;

use Service, Doctrine, Entity, Nette;
use Extras\Cache\Cache;
use Nette\Utils\Arrays;

class RentalSearchService extends Nette\Object 
{

	const COUNT_PER_PAGE			= 50;
	const CRITERIA_LOCATION  		= 'location';
	const CRITERIA_RENTAL_TYPE 		= 'rentalType';
	const CRITERIA_TAG	 			= 'rentalTag';
	const CRITERIA_CAPACITY 		= 'capacity';
	const CRITERIA_SPOKEN_LANGUAGE 	= 'spokenLanguage';
	const CRITERIA_PRICE 			= 'price';
	//const CRITERIA_AREA_BOUNDRY 	= 'areaBoundry';

	const CAPACITY_MAX				= 50;

	protected $primaryLocation;
	protected $criteria = array();
	protected $searchCacheData;
	protected $orderCacheData;

	protected $results;

	protected $rentalSearchCache;
	protected $rentalOrderCaching;
	protected $rentalRepositoryAccessor;

	public function __construct(\Entity\Location\Location $primaryLocation, Cache $rentalSearchCache, \Extras\Cache\IRentalOrderCachingFactory $rentalOrderCachingFactory) {
		$this->primaryLocation = $primaryLocation;

		$this->rentalSearchCache = $rentalSearchCache;
		$this->rentalOrderCaching = $rentalOrderCachingFactory->create($primaryLocation);
	}

	public function inject(\Nette\DI\Container $container) {
		$this->rentalRepositoryAccessor = $container->rentalRepositoryAccessor;
	}

	public function getCriteriumOptions($criterium) {
		$this->loadCache();
		return array_keys($this->searchCacheData[$criterium]);
	}

	// Criteria
	// ==============================
	public function resetCriteria() {
		$this->criteria = array();
		$this->resetResults();
	}

	public function setLocationCriterium(Entity\Location\Location $location = NULL) {
		$this->criteria[self::CRITERIA_LOCATION] = $location;
		$this->resetResults();
	}

	public function setRentalTypeCriterium(Entity\Rental\Type $rentalType = NULL) {
		$this->criteria[self::CRITERIA_RENTAL_TYPE] = $rentalType;
		$this->resetResults();
	}

	public function setRentalTagCriterium(Entity\Rental\Tag $tag = NULL) {
		$this->criteria[self::CRITERIA_TAG] = $tag;
		$this->resetResults();
	}

	public function setCapacityCriterium($capacity = NULL) {
		$this->criteria[self::CRITERIA_CAPACITY] = $capacity;
		$this->resetResults();
	}

	public function setSpokenLanguageCriterium(Entity\Language $spokenLanguage = NULL) {
		$this->criteria[self::CRITERIA_SPOKEN_LANGUAGE] = $spokenLanguage;
		$this->resetResults();
	}

	public function setPriceCriterium($price = NULL) {
		$this->criteria[self::CRITERIA_PRICE] = $price;
		$this->resetResults();
	}

	//todo - pridat funkcie na vyhodenie kriteria

	public function getRentalIds($page = NULL) {
		$this->getResults();
		$this->reorderResults();

		if ($page === NULL) {
			return $this->results;
		} else {
			$results = array_chunk($this->results, self::COUNT_PER_PAGE);
			return isset($results[$page]) ? $results[$page] : NULL;
		}
	}

	public function getRentals($page = NULL) {
		$results = $this->getRentalIds($page);

		return $this->rentalRepositoryAccessor->get()->findById($results);
	}

	public function getRentalsCount() {
		$this->getResults();
		return count($this->results);
	}

	//=================================

	protected function resetResults() {
		$this->results = NULL;
	}

	protected function loadCache() {
		// Load the cache data when first needed (lazy)
		if (!$this->searchCacheData) {
			$this->searchCacheData = $this->rentalSearchCache->load($this->primaryLocation->id);
		}
	}

	protected function getResults() {
		$this->loadCache();

		/// return the results if already loaded
		if ($this->results !== NULL) {
			return $this->results;
		}

		$results = array();

		foreach ($this->criteria as $key => $value) {
			if ($value === NULL) continue;
			$results[$key] = Arrays::get($this->searchCacheData, array($key, (is_object($value) ? $value->id : $value)), NULL);
		}

		$results = array_filter($results);

		if (count($results) > 1) {
			$tempResults = call_user_func_array('array_intersect', $results);			
		} else {
			$tempResults = reset($results);
		}

		if(is_array($tempResults)) {
			$this->results = $tempResults;
		} else {
			$this->results = array();
		}
		return $this->results;
	}

	protected function reorderResults() {
		$order = $this->rentalOrderCaching->getOrderList();
		$t = array();

		foreach ($this->results as $key => $value) {
			$t[$key] = $order[$key];
		}
		asort($t);

		$this->results = array_keys($t);
	}
}
