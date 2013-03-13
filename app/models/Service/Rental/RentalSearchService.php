<?php

namespace Service\Rental;

use Service, Doctrine, Entity, Nette;
use Extras\Cache\Cache;
use Nette\Utils\Arrays;

class RentalSearchService extends Nette\Object 
{

	const COUNT_PER_PAGE			= 10;
	const CRITERIA_LOCATION  		= 'location';
	const CRITERIA_RENTAL_TYPE 		= 'rentalType';

	const CRITERIA_CAPACITY 		= 'fcapacity';
	const CRITERIA_SPOKEN_LANGUAGE 	= 'flanguage';
	const CRITERIA_PRICE 			= 'fprice';

	const CAPACITY_MAX				= 50;

	protected $primaryLocation;
	protected $criteria = array();
	protected $searchCacheData;
	protected $orderCacheData;

	protected $results;
	protected $resultsOrdered = FALSE;

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

	public function setFcapacityCriterium($capacity = NULL) {
		$this->criteria[self::CRITERIA_CAPACITY] = $capacity;
		$this->resetResults();
	}

	public function setFpriceCriterium($price = NULL) {
		$this->criteria[self::CRITERIA_PRICE] = $price;
		$this->resetResults();
	}

	public function setFlanguageCriterium(Entity\Language $spokenLanguage = NULL) {
		$this->criteria[self::CRITERIA_SPOKEN_LANGUAGE] = $spokenLanguage;
		$this->resetResults();
	}

	public function getRentalsIds($page = NULL) {
		$this->getResults();
		$this->reorderResults();
		if ($page === NULL) {
			return $this->results;
		} else {
			$page = $page-1;
			$results = array_chunk($this->results, self::COUNT_PER_PAGE);
			return isset($results[$page]) ? $results[$page] : NULL;
		}
	}

	public function getRentals($page = NULL) {
		$results = $this->getRentalsIds($page);

		return $this->rentalRepositoryAccessor->get()->findById($results);
	}

	public function getRentalsCount() {
		$this->getResults();
		return count($this->results);
	}

	public function getFeaturedRentalIds($limit = NULL) {

		$featured = $this->rentalOrderCaching->getFeaturedList();
		if ($limit === NULL) {
			$return =  $featured;
		} else {
			$return =  array_slice($featured, 0, $limit);
		}

		if ($limit !== NULL && count($return) < $limit) {
			$order = $this->rentalOrderCaching->getOrderList();
			$order = array_flip($order);
			$return = array_slice(array_unique(array_merge($return, $order)), 0, $limit);
		}
		return $return;
	}

	public function getFeaturedRentals($limit = NULL) {
		$results = $this->getFeaturedRentalIds($limit);

		return $this->rentalRepositoryAccessor->get()->findById($results);
	}


	//=================================

	protected function resetResults() {
		$this->results = NULL;
		$this->resultsOrdered = FALSE;
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
			if (count($results[$key]) == 0) {
				$this->results = array();
				return $this->results;
			}
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
		if ($this->resultsOrdered === TRUE) return TRUE;
		$order = $this->rentalOrderCaching->getOrderList();
		
		$t = array();

		foreach ($this->results as $key => $value) {
			$t[$key] = $order[$key];
		}
		asort($t);

		$this->results = array_keys($t);
		$this->resultsOrdered = TRUE;
	}
}
