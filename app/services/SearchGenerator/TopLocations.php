<?php

namespace SearchGenerator;


use Entity\Location\Location;
use Extras\Cache\Cache;
use Service\Rental\IRentalSearchServiceFactory;
use Service\Rental\RentalSearchService;

class TopLocations
{

	/**
	 * @var \Entity\Location\Location
	 */
	protected $primaryLocation;


	/**
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchFactory;


	/**
	 * @var \Service\Rental\RentalSearchService
	 */
	protected $search;


	/**
	 * @param \Entity\Location\Location $primaryLocation
	 * @param \Service\Rental\IRentalSearchServiceFactory $rentalSearchFactory
	 */
	public function __construct(Location $primaryLocation, IRentalSearchServiceFactory $rentalSearchFactory)
	{
		$this->primaryLocation = $primaryLocation;
		$this->rentalSearchFactory = $rentalSearchFactory;
	}

	/**
	 * @return \Service\Rental\RentalSearchService
	 */
	public function getSearch()
	{
		if (!$this->search) {
			$this->search = $this->rentalSearchFactory->create($this->primaryLocation);
		}

		return $this->search;
	}


	/**
	 * @param null $maxResults
	 * @param \Service\Rental\RentalSearchService $search
	 *
	 * @return mixed
	 */
	public function getResults($maxResults = NULL, RentalSearchService $search = NULL)
	{
		if(!$search) {
			$search = $this->getSearch();
		}

		$locations = $search->getCollectedResults(RentalSearchService::CRITERIA_LOCATION, $maxResults);

		return $locations;
	}


	/**
	 * @param $array
	 *
	 * @return mixed
	 */
	protected function sortArrayByNumberOfItems($array)
	{
		uasort($array, function ($a, $b) {
			return count($b) - count($a);
		});

		return $array;
	}

}
