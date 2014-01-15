<?php

namespace Extras\Cache;

use Nette\Caching;
use Service\Rental\RentalSearchService;
use Tralandia\BaseDao;
use Tralandia\Rental\Rentals;

class RentalOrderCaching extends \Nette\Object {

	const CACHE_LIFETIME = '';
	protected $cache;
	protected $cacheContent;
	protected $location;


	/**
	 * @var \Tralandia\Rental\Rentals
	 */
	private $rentals;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $locationDao;


	public function __construct($location, BaseDao $locationDao, Cache $orderCache, Rentals $rentals) {
		$this->location = $location;
		$this->cache = $orderCache;
		$this->rentals = $rentals;
		$this->locationDao = $locationDao;
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
		if (!isset($this->cacheContent['order']) || $this->cacheContent['order'] === NULL) {
			$order = $this->createOrderList();
		} else {
			$order = $this->cacheContent['order'];
		}

		return $order;
	}

	public function getOrderListByLastUpdate() {
		if (!isset($this->cacheContent['orderByLastUpdate']) || $this->cacheContent['orderByLastUpdate'] === NULL) {
			$order = $this->createOrderListByLastUpdate();
		} else {
			$order = $this->cacheContent['orderByLastUpdate'];
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
		$rentals = $this->rentals->findFeatured($this->location);
		foreach ($rentals as $key => $value) {
			$this->cacheContent['featured'][$value['id']] = $value['id'];
		}
		return $this->cacheContent['featured'];
	}

	protected function createOrderList() {
		$this->createFeaturedList();

		$featured = $this->cacheContent['featured'];

		$notFeatured = array();
		$rentals = $this->rentals->findByPrimaryLocation($this->location, \Entity\Rental\Rental::STATUS_LIVE, ['r.rank' => 'DESC']);
		foreach ($rentals as $key => $value) {
			$notFeatured[$value->id] = $value->id;
		}

		foreach ($featured as $key => $value) {
			unset($notFeatured[$key]);
		}

		$order = array_merge($featured, $notFeatured);

		$order = array_flip(array_values($order));
		$this->cacheContent['order'] = $order;

		$this->location->rentalCount = count($order);
		$this->locationDao->save($this->location);

		$this->save();
		return $order;
	}

	protected function createOrderListByLastUpdate() {
		$this->createFeaturedList();

		$featured = $this->cacheContent['featured'];

		$notFeatured = array();
		$rentals = $this->rentals->findByPrimaryLocation($this->location, \Entity\Rental\Rental::STATUS_LIVE, ['r.lastUpdate' => 'DESC']);
		foreach ($rentals as $key => $value) {
			$notFeatured[$value->id] = $value->id;
		}

		foreach ($featured as $key => $value) {
			unset($notFeatured[$key]);
		}

		$order = array_merge($featured, $notFeatured);

		$order = array_flip(array_values($order));
		$this->cacheContent['orderByLastUpdate'] = $order;

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
