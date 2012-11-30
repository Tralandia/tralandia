<?php

namespace Extras\Cache;

use Nette\Caching;
use Service\Rental\RentalSearchService;

class RentalSearchKeysCaching extends \Nette\Object {

	protected $searchCacheFactory;
	protected $rentalSearchKeysCache;
	protected $rental;
	protected $searchCache = array();
	
	public function __construct($rental, ISearchCacheFactory $searchCacheFactory) {
		$this->rental = $rental;
		$this->searchCacheFactory = $searchCacheFactory;
	}

	public function setCache(Caching\Cache $rentalSearchKeysCache) {
		$this->rentalSearchKeysCache = $rentalSearchKeysCache;
	}

	protected function getPrimaryLocationCache($primaryLocation) {
		if (!isset($this->searchCache[$primaryLocation->id])) {
			$this->searchCache[$primaryLocation->id] = $this->searchCacheFactory->create('RentalSearchCache'.$primaryLocation->id);
		}

		return $this->searchCache[$primaryLocation->id];
	}

	protected function removeRentalFromCache() {
		$currentKeys = $this->rentalSearchKeysCache->load($this->rental->id);

		if ($currentKeys !== NULL) {
			$searchCache = $this->getPrimaryLocationCache($currentKeys['primaryKey']);

			foreach ($currentKeys['keys'] as $key => $value) {
				unset($searchCache[$value][$this->rental->id]);
			}
		}
	}

	protected function addRentalToCache() {
		$searchCache = $this->getPrimaryLocationCache($this->rental->primaryLocation->id);

		$newKeys = array();
		// Set Locations
		foreach ($this->rental->locations as $key => $value) {
			$thisKey = RentalSearchService::CRITERIA_LOCATION.$value->id;
			$searchCache[$thisKey][$this->rental->id] = $this->rental->id;
			$newKeys[] = $thisKey;
		}

		// Set rental Type
		if ($this->rental->type) {
			$thisKey = RentalSearchService::CRITERIA_RENTAL_TYPE.$this->rental->type->id;
			$searchCache[$thisKey][$this->rental->id] = $this->rental->id;
			$newKeys[] = $thisKey;
		}

		// Set Tags
		foreach ($this->rental->tags as $key => $value) {
			$thisKey = RentalSearchService::CRITERIA_TAG.$value->id;
			$searchCache[$thisKey][$this->rental->id] = $this->rental->id;
			$newKeys[] = $thisKey;
		}

		// Set Max Capacity
		if ($this->rental->maxCapacity) {
			$t = $this->rental->maxCapacity >= RentalSearchService::CAPACITY_MAX ? RentalSearchService::CAPACITY_MAX : $this->rental->maxCapacity;
			$thisKey = RentalSearchService::CRITERIA_RENTAL_TYPE.$t;
			$searchCache[$thisKey][$this->rental->id] = $this->rental->id;
			$newKeys[] = $thisKey;
		}

		// Set Languages Spoken
		foreach ($this->rental->languagesSpoken as $key => $value) {
			$thisKey = RentalSearchService::CRITERIA_LANGUAGE_SPOKEN.$value->id;
			$searchCache[$thisKey][$this->rental->id] = $this->rental->id;
			$newKeys[] = $thisKey;
		}

		// Set Price
		if ($this->rental->priceSeason) {
			$searchInterval = $this->rental->primaryLocation->defaultCurrency->searchInterval;
			$t = ceil($this->rental->priceSeason / $searchInterval)*$searchInterval;
			$thisKey = RentalSearchService::CRITERIA_PRICE.$t;
			$searchCache[$thisKey][$this->rental->id] = $this->rental->id;
			$newKeys[] = $thisKey;
		}

		// Set Boundry box
		//@todo

		$rentalSearchKeysCache = array(
			'primaryKey' => $this->rental->primaryLocation->id,
			'keys' => $newKeys,
		);

		$this->rentalSearchKeysCache->save($this->rental->id, $rentalSearchKeysCache);
	}

}