<?php

namespace Extras\Cache;

use Nette\Caching;
use Service\Rental\RentalSearchService;

class RentalSearchKeysCaching extends \Nette\Object {

	protected $rentalSearchCachingFactory;
	protected $rental;
	protected $rentalSearchCaching = array();
	protected $locationRepositoryAccessor;
	
	public function inject(\Nette\DI\Container $dic) {
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
	}

	public function __construct($rental, IRentalSearchCachingFactory $rentalSearchCachingFactory) {
		$this->rental = $rental;
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
	}

	protected function getPrimaryLocationCache(\Entity\Location\Location $primaryLocation) {
		if (!isset($this->rentalSearchCaching[$primaryLocation->id])) {
			$this->rentalSearchCaching[$primaryLocation->id] = $this->rentalSearchCachingFactory->create($primaryLocation);
		}

		return $this->rentalSearchCaching[$primaryLocation->id];
	}

	public function updateRentalInCache() {
		$this->removeRentalFromCache();
		if($this->rental->status == \Entity\Rental\Rental::STATUS_LIVE) {
			$this->addRentalToCache();
		}
		return $this;
	}

	protected function removeRentalFromCache() {

		if ($currentKeys !== NULL) {
			$primaryLocation = $this->locationRepositoryAccessor->get()->find($currentKeys['primaryKey']);
			$rentalSearchCaching = $this->getPrimaryLocationCache($primaryLocation);
			foreach ($currentKeys['keys'] as $key => $value) {
				$rentalSearchCaching->removeRental($this->rental, $value);
			}
			$this->rentalSearchKeysCache->remove($this->rental->id);
		}

		return $this;
	}

	protected function addRentalToCache() {
		$rentalSearchCaching = $this->getPrimaryLocationCache($this->rental->primaryLocation);
		$tempCache = array();

		$newKeys = array();

		// Set Locality
		$t = $this->rental->address->locality;
		$thisKey = RentalSearchService::CRITERIA_LOCATION.$t->id;
		$rentalSearchCaching->addRental($this->rental, $thisKey);
		$newKeys[] = $thisKey;

		// Set Locations
		foreach ($this->rental->address->locations as $key => $value) {
			$thisKey = RentalSearchService::CRITERIA_LOCATION.$value->id;
			$rentalSearchCaching->addRental($this->rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set rental Type
		if ($this->rental->type) {
			$thisKey = RentalSearchService::CRITERIA_RENTAL_TYPE.$this->rental->type->id;
			$rentalSearchCaching->addRental($this->rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set Max Capacity
		if ($this->rental->maxCapacity) {
			$t = $this->rental->maxCapacity >= RentalSearchService::CAPACITY_MAX ? RentalSearchService::CAPACITY_MAX : $this->rental->maxCapacity;
			$thisKey = RentalSearchService::CRITERIA_RENTAL_TYPE.$t;
			$rentalSearchCaching->addRental($this->rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set Languages Spoken
		foreach ($this->rental->spokenLanguages as $key => $value) {
			$thisKey = RentalSearchService::CRITERIA_SPOKEN_LANGUAGE.$value->id;
			$rentalSearchCaching->addRental($this->rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set Price
		if ($this->rental->price) {
			$searchInterval = $this->rental->primaryLocation->defaultCurrency->searchInterval;
			$t = ceil($this->rental->price / $searchInterval)*$searchInterval;
			$thisKey = RentalSearchService::CRITERIA_PRICE.$t;
			
			$rentalSearchCaching->addRental($this->rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		d($newKeys);

		// Set Boundry box
		//@todo

		$rentalSearchKeysCache = array(
			'primaryKey' => $this->rental->primaryLocation->id,
			'keys' => $newKeys,
		);

		$this->rentalSearchKeysCache->save($this->rental->id, $rentalSearchKeysCache);

		return $this;
	}

}