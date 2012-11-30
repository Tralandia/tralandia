<?php

namespace Extras\Cache;

use Nette\Caching;
use Service\Rental\RentalSearchService;

class RentalSearchCaching extends \Nette\Object {

	protected $searchCache;
	protected $location;
	protected $rentalRepositoryAccessor;
	
	public function inject(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function __construct($location, ISearchCacheFactory $searchCacheFactory) {
		$this->location = $location;
		$this->searchCache = $searchCacheFactory->create('RentalSearchCache'.$location->id);
	}

	public function removeRental(\Entity\Rental\Rental $rental, $key) {
		unset($this->searchCache[$key][$rental->id]);
		return $this;
	}

	public function addRental(\Entity\Rental\Rental $rental, $key) {
		$this->searchCache[$key][$rental->id] = $rental->id;
		return $this;
	}

	public function createTopRentalList() {
		$rentals = $this->rentalRepositoryAccessor->get()->findFeatured($this->location);
		
	}

}