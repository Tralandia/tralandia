<?php

namespace Service\Rental;

use Service, Doctrine, Entity, Nette;
use Extras\Cache\Cache;
use Nette\Utils\Arrays;
use Robot\IUpdateRentalSearchCacheRobotFactory;
use Tralandia\BaseDao;

class RentalSearchService extends Nette\Object
{

	const COUNT_PER_PAGE = 50;

	const ALL = 'all';
	const CRITERIA_LOCATION = 'location';
	const CRITERIA_RENTAL_TYPE = 'rentalType';
	const CRITERIA_PLACEMENT = 'placement';

	const CRITERIA_CAPACITY = 'fcapacity';
	const CRITERIA_SPOKEN_LANGUAGE = 'flanguage';
	const CRITERIA_PRICE = 'fprice';
	const CRITERIA_BOARD = 'board';

	const CAPACITY_MAX = 50;

	/**
	 * @var \Entity\Location\Location
	 */
	protected $primaryLocation;

	/**
	 * @var array
	 */
	protected $criteria = array();

	protected $searchCacheData = NULL;
	protected $orderCacheData;

	protected $results;
	protected $resultsOrdered = FALSE;

	/**
	 * @var \Extras\Cache\Cache
	 */
	protected $rentalSearchCache;

	/**
	 * @var \Robot\IUpdateRentalSearchCacheRobotFactory
	 */
	protected $rentalSearchCacheRobotFactory;

	/**
	 * @var \Extras\Cache\RentalOrderCaching
	 */
	protected $rentalOrderCaching;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $rentalDao;


	public function __construct(\Entity\Location\Location $primaryLocation, Cache $rentalSearchCache,BaseDao $rentalDao,
								\Extras\Cache\IRentalOrderCachingFactory $rentalOrderCachingFactory,
								IUpdateRentalSearchCacheRobotFactory $rentalSearchCacheRobotFactory)
	{
		$this->primaryLocation = $primaryLocation;

		$this->rentalSearchCache = $rentalSearchCache;
		$this->rentalSearchCacheRobotFactory = $rentalSearchCacheRobotFactory;
		$this->rentalOrderCaching = $rentalOrderCachingFactory->create($primaryLocation);
		$this->rentalDao = $rentalDao;
	}

	/**
	 * @return \Entity\Location\Location
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}

	/**
	 * @param $criterion
	 *
	 * @return array
	 */
	public function getCriterionOptions($criterion)
	{
		$this->loadCache();

		return array_keys($this->searchCacheData[$criterion]);
	}

	// Criteria
	// ==============================
	public function resetCriteria()
	{
		$this->criteria = array();
		$this->resetResults();
	}

	public function setLocationCriterion(Entity\Location\Location $location = NULL)
	{
		$this->criteria[self::CRITERIA_LOCATION] = $location;
		$this->resetResults();
	}

	public function setRentalTypeCriterion(Entity\Rental\Type $rentalType = NULL)
	{
		$this->criteria[self::CRITERIA_RENTAL_TYPE] = $rentalType;
		$this->resetResults();
	}

	/*placement
	public function setPlacementCriterion(Entity\Rental\Placement $placement = NULL)
	{
		$this->criteria[self::CRITERIA_PLACEMENT] = $placement;
		$this->resetResults();
	}
	placement*/

	public function setCapacityCriterion($capacity = NULL)
	{
		$this->criteria[self::CRITERIA_CAPACITY] = $capacity;
		$this->resetResults();
	}

	public function setPriceCriterion($from = NULL, $to = NULL)
	{
		$from == NULL && $from = 0;

		if($to == NULL) {
			$to = $this->getMaxPriceCriterionOption();
		}

		if($from <= $to) {
			$this->criteria[self::CRITERIA_PRICE] = ['from' => $from, 'to' => $to];
			$this->resetResults();
		}
	}

	public function getMaxPriceCriterionOption()
	{
		$priceOptions = $this->getCriterionOptions(self::CRITERIA_PRICE);
		return max($priceOptions);
	}

	public function setSpokenLanguageCriterion(Entity\Language $spokenLanguage = NULL)
	{
		$this->criteria[self::CRITERIA_SPOKEN_LANGUAGE] = $spokenLanguage;
		$this->resetResults();
	}

	public function setBoardCriterion(Entity\Rental\Amenity $board = NULL)
	{
		$this->criteria[self::CRITERIA_BOARD] = $board;
		$this->resetResults();
	}

	public function getRentalsIds($page = NULL)
	{
		$this->getResults();
		$this->reorderResults();
		if ($page === NULL) {
			return $this->results;
		} else {
			$page = $page - 1;
			$results = array_chunk($this->results, self::COUNT_PER_PAGE);

			return isset($results[$page]) ? $results[$page] : NULL;
		}
	}


	/**
	 * @param null $page
	 *
	 * @return \Entity\Rental\Rental[]
	 */
	public function getRentals($page = NULL)
	{
		$results = $this->getRentalsIds($page);

		$rentalsEntities = $this->rentalDao->findById($results);

		return \Tools::sortArrayByArray($rentalsEntities, $results, function($v) {return $v->getId();});
	}

	public function getRentalsCount()
	{
		$this->getResults();

		return count($this->results);
	}

	public function getFeaturedRentalIds($limit = NULL)
	{

		$featured = $this->rentalOrderCaching->getFeaturedList();
		if ($limit === NULL) {
			$return = $featured;
		} else {
			$return = array_slice($featured, 0, $limit);
		}

		if ($limit !== NULL && count($return) < $limit) {
			$order = $this->rentalOrderCaching->getOrderList();
			$order = array_flip($order);
			$return = array_slice(array_unique(array_merge($return, $order)), 0, $limit);
		}

		return $return;
	}

	public function getFeaturedRentals($limit = NULL)
	{
		$results = $this->getFeaturedRentalIds($limit);

		if(count($results)) {
			$sort = $results;
			$results = $this->rentalDao->findById($results);
			$results = \Tools::sortArrayByArray($results, $sort, function($value) {return $value->getId();});
		} else {
			$results = [];
		}

		return $results;
	}


	/**
	 * @param $criterionType
	 *
	 * @return array
	 */
	public function getCollectedResults($criterionType)
	{
		$this->loadCache();

		if(!array_key_exists($criterionType, $this->searchCacheData)) return [];
		$results = $this->getResults();

		$collection = [];
		foreach($this->searchCacheData[$criterionType] as $key => $value) {
			$collection[$key] = array_intersect($results, $value);
		}

		return array_filter($collection);
	}


	//=================================

	protected function resetResults()
	{
		$this->results = NULL;
		$this->resultsOrdered = FALSE;
	}

	protected function loadCache()
	{
		// Load the cache data when first needed (lazy)
		if ($this->searchCacheData === NULL) {
			$searchCacheData = $this->rentalSearchCache->load($this->getPrimaryLocation()->getId());

			if(!$searchCacheData || !count($searchCacheData)) {
				$this->rentalSearchCacheRobotFactory->create($this->getPrimaryLocation())->run();
				$searchCacheData = $this->rentalSearchCache->load($this->getPrimaryLocation()->getId());
			}

			$this->searchCacheData = $searchCacheData;
		}
	}

	protected function getResults()
	{
		$this->loadCache();

		/// return the results if already loaded
		if ($this->results !== NULL) {
			return $this->results;
		}

		$results = array();
		if (count($this->criteria)) {
			foreach ($this->criteria as $key => $value) {
				if ($value === NULL || (is_array($value) && !count(array_filter($value)))) continue;

				if ($key == self::CRITERIA_PRICE) {
					$byPrice = [];
					$priceOptions = $this->getCriterionOptions(self::CRITERIA_PRICE);
					foreach($priceOptions as $priceOption) {
						if($priceOption > $value['from'] && $priceOption <= $value['to']) {
							$byPrice += Arrays::get($this->searchCacheData, array($key, $priceOption), NULL);
						}
					}
					$results[$key] = $byPrice;
				} else {
					$results[$key] = Arrays::get($this->searchCacheData, array($key, (is_object($value) ? $value->id : $value)), NULL);
				}

				if (count($results[$key]) == 0) {
					$this->results = array();

					return $this->results;
				}
			}

			$results = array_filter($results);

			if (count($results) > 1) {
				$results = call_user_func_array('array_intersect', $results);
			} else {
				$results = reset($results);
			}

		} else {
			$results = Arrays::get($this->searchCacheData, self::ALL, NULL);
		}


		if (is_array($results)) {
			$this->results = $results;
		} else {
			$this->results = array();
		}

		return $this->results;
	}

	protected function reorderResults()
	{
		if ($this->resultsOrdered === TRUE) return TRUE;
		$order = $this->rentalOrderCaching->getOrderList();

		$t = array();

		foreach ($this->results as $key => $value) {
			$t[$key] = $order[$key];
		}
		asort($t);

		$this->results = array_keys($t);
		$this->resultsOrdered = TRUE;
	}
}
