<?php

namespace SearchGenerator;


use Entity\Location\Location;
use Extras\Cache\Cache;
use Service\Rental\RentalSearchService;

class TopLocations {

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
	 * @param null $maxResults
	 *
	 * @return mixed
	 */
	public function getResults($maxResults = NULL)
	{
		$locations = $this->cacheData[RentalSearchService::CRITERIA_LOCATION];

		$locations = $this->sortArrayByNumberOfItems($locations);

		if(is_numeric($maxResults)) {
			$locations = array_chunk($locations, $maxResults, TRUE)[0];
		}

		return $locations;
	}

	/**
	 * @param $array
	 *
	 * @return mixed
	 */
	protected function sortArrayByNumberOfItems($array)
	{
		uasort($array, function($a, $b) { return count($b) - count($a); });
		return $array;
	}

}