<?php

namespace Extras\Cache;

use Entity\Rental\Rental;
use Nette\Caching;
use Service\Rental\RentalSearchService;

class RentalSearchKeysCaching extends \Nette\Object
{

	protected $rentalSearchCachingFactory;

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Extras\Cache\RentalSearchCaching
	 */
	protected $rentalSearchCaching;

	protected $locationRepositoryAccessor;


	public function inject(\Nette\DI\Container $dic)
	{
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
	}


	public function __construct(Rental $rental, IRentalSearchCachingFactory $rentalSearchCachingFactory)
	{
		$this->rental = $rental;
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
	}


	/**
	 * @param \Entity\Location\Location $primaryLocation
	 *
	 * @return \Extras\Cache\RentalSearchCaching
	 */
	protected function getPrimaryLocationCache(\Entity\Location\Location $primaryLocation)
	{
		if (!isset($this->rentalSearchCaching[$primaryLocation->id])) {
			$this->rentalSearchCaching[$primaryLocation->id] = $this->rentalSearchCachingFactory->create($primaryLocation);
		}

		return $this->rentalSearchCaching[$primaryLocation->id];
	}


	public function updateRentalInCache()
	{
		$this->removeRentalFromCache();
		if ($this->rental->status == \Entity\Rental\Rental::STATUS_LIVE) {
			$this->addRentalToCache();
		}

		return $this;
	}


	protected function removeRentalFromCache()
	{

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


	protected function addRentalToCache()
	{
		$rentalSearchCaching = $this->getPrimaryLocationCache($this->rental->primaryLocation);

		$newKeys = array();

		$rental = $this->rental;

		// Set Locality
		$t = $rental->address->locality;
		$thisKey = RentalSearchService::CRITERIA_LOCATION . $t->id;
		$rentalSearchCaching->addRental($rental, $thisKey);
		$newKeys[] = $thisKey;

		// Set Locations
		foreach ($rental->address->locations as $key => $value) {
			$thisKey = RentalSearchService::CRITERIA_LOCATION . $value->id;
			$rentalSearchCaching->addRental($rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set rental Type
		if ($rental->type) {
			$thisKey = RentalSearchService::CRITERIA_RENTAL_TYPE . $rental->type->id;
			$rentalSearchCaching->addRental($rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set Max Capacity
		if ($rental->maxCapacity) {
			$t = $rental->maxCapacity >= RentalSearchService::CAPACITY_MAX ? RentalSearchService::CAPACITY_MAX : $rental->maxCapacity;
			$thisKey = RentalSearchService::CRITERIA_RENTAL_TYPE . $t;
			$rentalSearchCaching->addRental($rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set Languages Spoken
		foreach ($rental->spokenLanguages as $key => $value) {
			$thisKey = RentalSearchService::CRITERIA_SPOKEN_LANGUAGE . $value->id;
			$rentalSearchCaching->addRental($rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set Price
		if ($rental->price) {
			$searchInterval = $rental->primaryLocation->defaultCurrency->searchInterval;
			$t = ceil($rental->price / $searchInterval) * $searchInterval;
			$thisKey = RentalSearchService::CRITERIA_PRICE . $t;

			$rentalSearchCaching->addRental($rental, $thisKey);
			$newKeys[] = $thisKey;
		}

		// Set Board
		$boards = $rental->getBoardAmenities();
		if(is_array($boards)) {
			foreach($boards as $board) {
				$thisKey = RentalSearchService::CRITERIA_BOARD . $board->getId();
				$rentalSearchCaching->addRental($rental, $thisKey);
				$newKeys[] = $thisKey;
			}
		}


		//d($newKeys);

		// Set Boundry box
		//@todo

		$rentalSearchKeysCache = array(
			'primaryKey' => $rental->primaryLocation->id,
			'keys' => $newKeys,
		);

		$this->rentalSearchKeysCache->save($rental->id, $rentalSearchKeysCache);

		return $this;
	}

}
