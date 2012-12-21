<?php

namespace Extras\Cache;

use Nette\Caching;
use Service\Rental\RentalSearchService;

class RentalSearchCaching extends \Nette\Object {

	const CACHE_LIFETIME = '';
	protected $cache;
	protected $cacheContent;
	protected $cacheLoaded = FALSE;
	protected $location;
	protected $rentalRepositoryAccessor;
	
	public function inject(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function __construct($location, Cache $searchCache) {
		$this->location = $location;
		$this->cache = $searchCache;
		$this->load();
	}

	protected function load() {
		if (!$this->cacheLoaded) {
			$this->cacheContent = $this->cache->load($this->location->id);
			if ($this->cacheContent === NULL) {
				$this->cacheContent = array();
			}
			$this->cacheLoaded = TRUE;			
		}
	}

	public function save() {
		if ($this->cacheLoaded) {
			$this->cache->save($this->location->id, $this->cacheContent);		
		}
	}

	protected function removeRental(\Entity\Rental\Rental $rental) {
		foreach ($this->cacheContent as $key => $value) {
			foreach ($value as $key2 => $value2) {
				if (isset($value2[$rental->id])) unset($this->cacheContent[$key][$key2][$rental->id]);
			}
		}
	}

	public function addRental(\Entity\Rental\Rental $rental) {
		$this->removeRental($rental);

		if($rental->status != \Entity\Rental\Rental::STATUS_LIVE) {
			throw new \Nette\InvalidArgumentException('Len live rental mozes ulozit do cache');
		}

		// Set Locality
		$this->cacheContent[RentalSearchService::CRITERIA_LOCATION][$rental->address->locality->id][$rental->id] = $rental->id;

		// Set Locations
		foreach ($rental->address->locations as $key => $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_LOCATION][$value->id][$rental->id] = $rental->id;
		}

		// Set rental Type
		if ($rental->type) {
			$this->cacheContent[RentalSearchService::CRITERIA_RENTAL_TYPE][$rental->type->id][$rental->id] = $rental->id;
		}

		// Set Tags
		foreach ($rental->tags as $key => $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_TAG][$value->id][$rental->id] = $rental->id;
		}

		// Set Max Capacity
		if ($rental->maxCapacity) {
			$t = $rental->maxCapacity >= RentalSearchService::CAPACITY_MAX ? RentalSearchService::CAPACITY_MAX : $rental->maxCapacity;

			$this->cacheContent[RentalSearchService::CRITERIA_CAPACITY][$t][$rental->id] = $rental->id;
		}

		// Set Languages Spoken
		foreach ($rental->spokenLanguages as $key => $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE][$value->id][$rental->id] = $rental->id;
		}

		// Set Price
		if ($rental->priceSeason) {
			$searchInterval = $rental->primaryLocation->defaultCurrency->searchInterval;
			$t = ceil($rental->priceSeason / $searchInterval)*$searchInterval;
			$this->cacheContent[RentalSearchService::CRITERIA_PRICE][$t][$rental->id] = $rental->id;
		}
		$this->save();
	}
}