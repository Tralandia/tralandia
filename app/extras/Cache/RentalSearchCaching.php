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
		$tempCache = $this->searchCache->load($key);
		unset($tempCache[$rental->id]);
		$this->searchCache->save($key, $tempCache);
		return $this;
	}

	public function addRental(\Entity\Rental\Rental $rental, $key) {
		$tempCache = $this->searchCache->load($key);
		$tempCache[$rental->id] = $rental->id;
		$this->searchCache->save($key, $tempCache);
		return $this;
	}

	public function createRentalFeaturedList() {
		$featured = array();
		$rentals = $this->rentalRepositoryAccessor->get()->findFeatured($this->location);
		foreach ($rentals as $key => $value) {
			$featured[$value['id']] = $value['id'];
		}
		$this->searchCache['featured'] = $featured;

		return $featured;
	}

	public function createRentalOrderList() {
		$featured = $this->createRentalFeaturedList();

		$notFeatured = array();

		$rentals = $this->rentalRepositoryAccessor->get()->find(array('primaryLocation' => $this->location, 'status' => \Entity\Rental\Rental::STATUS_LIVE));
		foreach ($rentals as $key => $value) {
			$notFeatured[$value['id']] = $value['id'];
		}

		foreach ($featured as $key => $value) {
			unset($notFeatured[$key]);
		}

		//@todo - this is just simple shuffle, but we need to make it more efficient so that: 
		// results are the same during the day (reconsider whether trully necessary)
		// better filled rentals should be higher with higher probability
		shuffle($featured);
		shuffle($notFeatured);

		$order = array_merge($featured, $notFeatured);

		$this->searchCache['order'] = $order;

		return $order;
	}

	public function isFeatured(\Entity\Rental\Rental $rental) {
		return isset($this->searchCache['featured'][$rental->id]);
	}
}