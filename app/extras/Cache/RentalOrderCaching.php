<?php

namespace Extras\Cache;

use Nette\Caching;
use Service\Rental\RentalSearchService;

class RentalOrderCaching extends \Nette\Object {

	const CACHE_LIFETIME = '';
	protected $cache;
	protected $cacheContent;
	protected $location;
	protected $rentalRepositoryAccessor;
	
	public function inject(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function __construct($location, Cache $orderCache) {
		$this->location = $location;
		$this->cache = $orderCache;
		$this->load();
	}

	protected function load() {
		$this->cacheContent = $this->cache->load($this->location->id);
	}

	public function reset() {
		$this->cacheContent = NULL;
	}

	public function save() {
		if ($this->cacheContent !== NULL) {
			$this->cache->save($this->location->id, $this->cacheContent, array(
				Caching\Cache::EXPIRE => $this->getExpirationTimeStamp(),
			));
		}
	}

	public function getOrderList() {
		if ($this->cacheContent['order'] === NULL) {
			$order = $this->createOrderList();
		} else {
			$order = $this->cacheContent['order'];
		}

		return $order;
	}

	public function getFeaturedList() {
		if ($this->cacheContent['featured'] === NULL) {
			$featured = $this->createFeaturedList();
		} else {
			$featured = $this->cacheContent['featured'];
		}

		return $featured;
	}

	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return bool
	 */
	public function isFeatured(\Entity\Rental\Rental $rental) {
		return isset($this->cacheContent['featured'][$rental->id]);
	}

	protected function createFeaturedList() {
		// Clear the featured list first
		$this->cacheContent['featured'] = array();
		$rentals = $this->rentalRepositoryAccessor->get()->findFeatured($this->location);
		foreach ($rentals as $key => $value) {
			$this->cacheContent['featured'][$value['id']] = $value['id'];
		}
		return $this->cacheContent['featured'];
	}

	protected function createOrderList() {
		d('ts');
		$this->createFeaturedList();

		$featured = $this->cacheContent['featured'];

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

		$order = array_merge($featured, $notFeatured);

		$order = array_flip(array_values($order));
		$this->cacheContent['order'] = $order;

		$this->location->rentalCount = count($order);
		$this->rentalRepositoryAccessor->get()->flush();

		$this->save();
		return $order;
	}

	protected function getExpirationTimeStamp() {
		$t = strtotime('next hour');
		$t = mktime (date("H", $t), 0, 0, date("n", $t), date("j", $t), date("Y", $t));
		return $t;
	}
}

interface IRentalOrderCachingFactory {
	/**
	 * @param \Entity\Location\Location $location
	 *
	 * @return RentalOrderCaching
	 */
	public function create(\Entity\Location\Location $location);
}
