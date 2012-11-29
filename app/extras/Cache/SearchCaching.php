<?php

namespace Extras\Cache;


use Nette\Caching;
use Service\Rental\RentalSearchService;

// $cache['location'][123] = 'liptov'
// $cache['rentalType'][144][12] = 'chaty'
// $cache['tag'][144][567] = 'lacne'


class SearchCaching extends \Nette\Object {

	const CACHE_EXPIRE = '1 hour';

	protected $primaryLocation;

	protected $criteria;
	protected $value;

	protected $rentalRepositoryAccessor;
	
	public function __construct($location) {
		$this->primaryLocation = $location;
	}

	public function setCache(Caching\Cache $cache) {
		$this->cache = $cache;
	}

	public function setCriteria($criteria) {
		$this->criteria = $criteria;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function inject(\Nette\DI\Container $container) {
		$this->rentalRepositoryAccessor = $container->rentalRepositoryAccessor;
	}

	public function save($ids) {

	}
	public function isValid() {
		return false;
	}	

	public function findRentalIdsBy() {

		$cacheKey = $this->getCacheKey();
		$ids = $this->cache->load($cacheKey);

		if ($ids === NULL) {

			$ids = array();
			switch($this->criteria) {
				case RentalSearchService::CRITERIA_LOCATION:
				case RentalSearchService::CRITERIA_AMENITIES:
				case RentalSearchService::CRITERIA_LANGUAGES_SPOKEN:
					foreach($this->value->getRentals() as $rental) {
						$ids[] = $rental->getId();
					}
					break;

				case RentalSearchService::CRITERIA_RENTAL_TYPE:
					foreach($this->rentalRepositoryAccessor->get()->findByType($this->value) as $rental) {
						$ids[] = $rental->getId();
					}
					break;

				case RentalSearchService::CRITERIA_CAPACITY:
					// $this->rentalRepositoryAccessor->get()->findByCapacity();
					break;

				case RentalSearchService::CRITERIA_PRICE_CATEGORY:
					
					break;

				case RentalSearchService::CRITERIA_AREA_BOUNDRIES:
					
					break;

				default:
					return FALSE;
					break;
			}

			$this->cache->save($cacheKey, $ids, array(Caching\Cache::EXPIRE => self::CACHE_EXPIRE));
		}

		return $ids;

	}

	private function getCacheKey() {
		return $this->primaryLocation->getId() . '_' . $this->criteria . '_' . $this->value->getId();
	}

}