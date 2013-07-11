<?php

namespace Extras\Cache;

use Doctrine\ORM\EntityManager;
use Entity\Location\Location;
use Entity\Rental\Rental;
use Nette\Caching;
use Service\Rental\RentalSearchService;

class RentalSearchCaching extends \Nette\Object {

	const CACHE_LIFETIME = '';

	/**
	 * @var Cache
	 */
	protected $cache;

	protected $cacheContent;
	protected $cacheLoaded = FALSE;

	/**
	 * @var \Entity\Location\Location
	 */
	protected $location;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;


	public function __construct(Location $location, Cache $searchCache, EntityManager $em) {
		$this->em = $em;
		$this->location = $location;
		$this->cache = $searchCache;
		$this->load();
	}

	protected function load() {
		if (!$this->cacheLoaded) {
			$this->cacheContent = $this->cache->load($this->location->id);
			if ($this->cacheContent === NULL) {
				$this->cacheContent = array();
			}
			$this->cacheLoaded = TRUE;
		}
	}

	public function save() {
		if ($this->cacheLoaded) {
			$this->cache->save($this->location->id, $this->cacheContent);
		}
	}

	protected function removeRental(\Entity\Rental\Rental $rental) {
		foreach ($this->cacheContent as $key => $value) {
			foreach ($value as $key2 => $value2) {
				if (isset($value2[$rental->id])) unset($this->cacheContent[$key][$key2][$rental->id]);
				if (count($this->cacheContent[$key][$key2]) == 0) unset($this->cacheContent[$key][$key2]);
			}
		}
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


	public function regenerate()
	{
		$data = [];

		$rentalRepository = $this->em->getRepository(RENTAL_ENTITY);

		$qb = $rentalRepository->createQueryBuilder('r');
		$qb->select('r.id AS rentalId, l.id AS locationId')
			->innerJoin('r.address', 'a')
			->innerJoin('a.locations', 'l')
			->where('a.primaryLocation = ?1')->setParameter(1, $this->location->getId());

		$rentalsLocations = $qb->getQuery()->getResult();

		foreach($rentalsLocations as $value) {
			$data[RentalSearchService::CRITERIA_LOCATION][$value['locationId']][$value['rentalId']] = $value['rentalId'];
		}

		return $data;


	}


	public function addRental(\Entity\Rental\Rental $rental) {
		$this->removeRental($rental);

		if($rental->status != \Entity\Rental\Rental::STATUS_LIVE) {
			$this->save();
			throw new \Nette\InvalidArgumentException('Len live rental mozes ulozit do cache');
		}

		// Set Locality
		$this->cacheContent[RentalSearchService::CRITERIA_LOCATION][$rental->address->locality->id][$rental->id] = $rental->id;

		// Set Locations
		foreach ($rental->address->locations as $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_LOCATION][$value->id][$rental->id] = $rental->id;
		}

		// Set rental Type
		if ($rental->type) {
			$this->cacheContent[RentalSearchService::CRITERIA_RENTAL_TYPE][$rental->type->id][$rental->id] = $rental->id;
		}

		/*placement
		// Set Placement
		foreach ($rental->getPlacements() as $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_PLACEMENT][$value->getId()][$rental->getId()] = $rental->getId();
		}
		placement*/

		// Set Max Capacity
		if ($rental->maxCapacity) {
			$t = $rental->maxCapacity >= RentalSearchService::CAPACITY_MAX ? RentalSearchService::CAPACITY_MAX : $rental->maxCapacity;

			for($i = 1; $i <= $t; $i++) {
				$this->cacheContent[RentalSearchService::CRITERIA_CAPACITY][$i][$rental->id] = $rental->id;
			}
		}

		// Set Languages Spoken
		foreach ($rental->spokenLanguages as $value) {
			$this->cacheContent[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE][$value->id][$rental->id] = $rental->id;
		}

		// Set Price
		if ($rental->price) {
			$searchInterval = $rental->primaryLocation->defaultCurrency->searchInterval;
			$t = ceil($rental->price->getSourceAmount() / $searchInterval)*$searchInterval;
			$this->cacheContent[RentalSearchService::CRITERIA_PRICE][$t][$rental->id] = $rental->id;
		}

		// Set Board
		$boards = $rental->getBoardAmenities();
		if(is_array($boards)) {
			foreach($boards as $board) {
				$this->cacheContent[RentalSearchService::CRITERIA_BOARD][$board->getId()][$rental->getId()] = $rental->getId();
			}
		}

		$this->cacheContent[RentalSearchService::ALL][$rental->getId()] = $rental->getId();

		$this->save();
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

