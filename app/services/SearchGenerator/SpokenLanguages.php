<?php

namespace SearchGenerator;


use Entity\Location\Location;
use Extras\Cache\Cache;
use Service\Rental\RentalSearchService;

class SpokenLanguages {

	/**
	 * @var mixed|NULL
	 */
	protected $cacheData;

	/**
	 * @param \Entity\Location\Location $primaryLocation
	 * @param \Extras\Cache\Cache $rentalSearchCache
	 */
	public function __construct(Location $primaryLocation, Cache $rentalSearchCache) {
		$this->cacheData = $rentalSearchCache->load($primaryLocation->getId());
	}


	/**
	 * @return array
	 */
	public function getUsed()
	{
		return array_keys($this->cacheData[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE]);
	}

}
