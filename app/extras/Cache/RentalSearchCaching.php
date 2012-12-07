<?php

namespace Extras\Cache;

use Nette\Caching;
use Service\Rental\RentalSearchService;

class RentalSearchCaching extends \Nette\Object {

	const CACHE_LIFETIME = '';
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

	public function load($key) {
		return $this->searchCache->load($key);
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

	public function getOrderList() {
		$order = $this->searchCache->load('order');

		if (true || $order === NULL) {
			$order = $this->createRentalOrderList();
		}

		return $order;
	}

	protected function createRentalFeaturedList() {
		$featured = array();
		$rentals = $this->rentalRepositoryAccessor->get()->findFeatured($this->location);
		foreach ($rentals as $key => $value) {
			$featured[$value['id']] = $value['id'];
		}
		$this->searchCache->save('featured', $featured, array(
			Caching\Cache::EXPIRE => $this->getExpirationTimeStamp(),
		));

		return $featured;
	}

	protected function createRentalOrderList() {
		$featured = $this->createRentalFeaturedList();

		$notFeatured = array();

		$rentals = $this->rentalRepositoryAccessor->get()->findBy(array('primaryLocation' => $this->location, 'status' => \Entity\Rental\Rental::STATUS_LIVE));
		foreach ($rentals as $key => $value) {
			$notFeatured[$value->id] = $value->id;
		}

		foreach ($featured as $key => $value) {
			unset($notFeatured[$key]);
		}

		//@todo - this is just simple shuffle, but we need to make it more efficient so that: 
		// results are the same during the day (reconsider whether trully necessary)
		// better filled rentals should be higher with higher probability
		shuffle($featured);
		shuffle($notFeatured);
d($featured, $notFeatured);
		$order = array_merge($featured, $notFeatured);
		d($order);
		$order = array_flip(array_values($order));
		$this->searchCache->save('order', $order, array(
			Caching\Cache::EXPIRE => $this->getExpirationTimeStamp(),
		));

		return $order;
	}

	public function invalidateRentalOrderList() {
		$this->searchCache->remove('order');
	}

	public function isFeatured(\Entity\Rental\Rental $rental) {
		$t = $this->searchCache->load('featured');
		return isset($t[$rental->id]);
	}

	protected function getExpirationTimeStamp() {
		$t = strtotime('next hour');
		$t = mktime (date("H", $t), 0, 0, date("n", $t), date("j", $t), date("Y", $t));
		return $t;
	}
}