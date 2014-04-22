<?php

namespace Extras\Cache;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Entity\Location\Location;
use Entity\Rental\Rental;
use Nette\Caching;
use Service\Rental\RentalSearchService;
use Tralandia\Rental\Rentals;

class RentalSearchCaching extends \Nette\Object {

	const CACHE_LIFETIME = '';

	/**
	 * @var Cache
	 */
	protected $cache;

	protected $cacheContent;

	/**
	 * @var \Entity\Location\Location
	 */
	protected $location;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \Tralandia\Rental\Rentals
	 */
	private $rentals;


	/**
	 * @param Location $location
	 * @param Cache $searchCache
	 * @param EntityManager $em
	 * @param Rentals $rentals
	 */
	public function __construct(Location $location, Cache $searchCache, EntityManager $em, Rentals $rentals) {
		$this->em = $em;
		$this->location = $location;
		$this->cache = $searchCache;
		$this->rentals = $rentals;
		$this->load();
	}

	protected function load() {
		$this->cacheContent = $this->cache->load($this->location->id);
		if ($this->cacheContent === NULL) {
			$this->cacheContent = array();
		}
	}

	public function save() {
		$this->cache->save($this->location->id, $this->cacheContent);
	}


	/**
	 * @param Rental $rental
	 */
	public function removeRental(Rental $rental) {
		foreach ($this->cacheContent as $key => $value) {
			if($key == RentalSearchService::ALL) {
				if (isset($value[$rental->getId()])) unset($this->cacheContent[$key][$rental->getId()]);
			} else {
				foreach ($value as $key2 => $value2) {
					if (isset($value2[$rental->getId()])) {
						unset($this->cacheContent[$key][$key2][$rental->getId()]);
					}
					if (count($this->cacheContent[$key][$key2]) == 0) {
						unset($this->cacheContent[$key][$key2]);
					}
				}
			}
		}
	}


	/**
	 * @param Rental $rental
	 */
	public function deleteRental(Rental $rental)
	{
		$this->removeRental($rental);
		$this->save();
	}


	public function getRentalCacheInfo(Rental $rental)
	{
		$return = [];
		foreach ($this->cacheContent as $key => $value) {
			foreach ($value as $key2 => $value2) {
				if (isset($value2[$rental->id])) $return[$key][$key2] = $key2;
			}
		}

		return $return;
	}


	public function updateWholeCache()
	{
		$this->regenerateData();
		$this->save();
	}


	/**
	 * @param Rental $rental
	 */
	public function updateRental(Rental $rental)
	{
		try {
			$this->addRental($rental);
		} catch (RentalMustByLiveException $e) {

		}
	}


	/**
	 * @param Rental $rental
	 *
	 * @throws RentalMustByLiveException
	 */
	public function addRental(Rental $rental) {
		$this->removeRental($rental);

		if(!$rental->isLive()) {
			$this->save(); // aby sa ulozili to vyhodenie objektu
			//throw new RentalMustByLiveException('Len live rental mozes ulozit do cache');
			return null;
		}

		$this->regenerateData($rental);

		$this->save();
	}


	/**
	 * @param Rental $rental
	 */
	public function regenerateData(Rental $rental = NULL)
	{
		$baseQb = $this->rentals->rentalsInSearchBaseQb($this->location);

		if($rental) {
			$baseQb->andWhere('r.id = :onlyForRental')->setParameter('onlyForRental', $rental->getId());
		}

		$this->regenerateLocationsData(clone $baseQb);
		$this->regenerateRentalTypeData(clone $baseQb);
		$this->regenerateCapacityData(clone $baseQb);
		$this->regenerateLanguageSpokenData(clone $baseQb);
		$this->regeneratePriceData(clone $baseQb);
		$this->regenerateBoardData(clone $baseQb);
		$this->regenerateAllData(clone $baseQb);
	}


	/**
	 * @param QueryBuilder $qb
	 */
	private function regenerateAllData(QueryBuilder $qb)
	{
		$qb->select('r.id AS rentalId');
		$allRentals = $qb->getQuery()->getResult();
		foreach($allRentals as $value) {
			$this->cacheContent[RentalSearchService::ALL][$value['rentalId']] = $value['rentalId'];
		}
	}


	/**
	 * @param QueryBuilder $qb
	 */
	private function regenerateBoardData(QueryBuilder $qb)
	{
		$qb->select('r.id AS rentalId, amenity.id AS boardId')
			->innerJoin('r.amenities', 'amenity')
			->innerJoin('amenity.type', 'amenityType')
			->andWhere('amenityType.slug = ?1')->setParameter('1', 'board');

		$rentalsBoard = $qb->getQuery()->getResult();
		foreach($rentalsBoard as $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_BOARD][$value['boardId']][$value['rentalId']] = $value['rentalId'];
		}
	}


	/**
	 * @param QueryBuilder $qb
	 */
	private function regeneratePriceData(QueryBuilder $qb)
	{
		$priceSearchInterval = $this->location->getDefaultCurrency()->getSearchInterval();
		$qb->select('r.id AS rentalId, r.price AS price');

		$rentalsPrice = $qb->getQuery()->getResult();
		foreach($rentalsPrice as $value) {
			$t = (int) ceil($value['price'] / $priceSearchInterval) * $priceSearchInterval;
			$this->cacheContent[RentalSearchService::CRITERIA_PRICE][$t][$value['rentalId']] = $value['rentalId'];
			$t2 = (int) ceil(($value['price'] - 1) / $priceSearchInterval) * $priceSearchInterval;
			if($t != $t2) {
				$this->cacheContent[RentalSearchService::CRITERIA_PRICE][$t2][$value['rentalId']] = $value['rentalId'];
			}
		}
	}


	/**
	 * @param QueryBuilder $qb
	 */
	private function regenerateLanguageSpokenData(QueryBuilder $qb)
	{
		$qb->select('r.id AS rentalId, l.id AS languageId')
			->innerJoin('r.spokenLanguages', 'l');

		$spokenLanguages = $qb->getQuery()->getResult();
		foreach($spokenLanguages as $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE][$value['languageId']][$value['rentalId']] = $value['rentalId'];
		}
	}


	/**
	 * @param QueryBuilder $qb
	 */
	private function regenerateCapacityData(QueryBuilder $qb)
	{
		$qb->select('r.id AS rentalId, r.maxCapacity AS maxCapacity');

		$rentalsCapacity = $qb->getQuery()->getResult();
		foreach($rentalsCapacity as $value) {
			$t = $value['maxCapacity'] >= RentalSearchService::CAPACITY_MAX ? RentalSearchService::CAPACITY_MAX : $value['maxCapacity'];

			for($i = 1; $i <= $t; $i++) {
				$this->cacheContent[RentalSearchService::CRITERIA_CAPACITY][$i][$value['rentalId']] = $value['rentalId'];
			}
		}
	}


	/**
	 * @param QueryBuilder $qb
	 */
	private function regenerateRentalTypeData(QueryBuilder $qb)
	{
		$qb->select('r.id AS rentalId, t.id AS typeId')
			->innerJoin('r.type', 't');

		$rentalsType = $qb->getQuery()->getResult();
		foreach($rentalsType as $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_RENTAL_TYPE][$value['typeId']][$value['rentalId']] = $value['rentalId'];
		}
	}


	/**
	 * @param QueryBuilder $baseQb
	 */
	private function regenerateLocationsData(QueryBuilder $baseQb)
	{
		// Locality
		$qb = clone $baseQb;
		$qb->select('r.id AS rentalId, l.id AS localityId')
			->innerJoin('a.locality', 'l');

		$rentalsLocalities = $qb->getQuery()->getResult();

		foreach($rentalsLocalities as $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_LOCATION][$value['localityId']][$value['rentalId']] = $value['rentalId'];
		}

		// Location
		$qb = clone $baseQb;
		$qb->select('r.id AS rentalId, l.id AS locationId')
			->innerJoin('a.locations', 'l');

		$rentalsLocation = $qb->getQuery()->getResult();

		foreach($rentalsLocation as $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_LOCATION][$value['locationId']][$value['rentalId']] = $value['rentalId'];
		}
	}


}


interface IRentalSearchCachingFactory {
	/**
	 * @param \Entity\Location\Location $location
	 *
	 * @return RentalSearchCaching
	 */
	public function create(\Entity\Location\Location $location);
}


class RentalMustByLiveException extends \InvalidArgumentException {}
