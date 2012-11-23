<?php

namespace Extras\Cache;


use Nette\Caching;

// $cache['location'][123] = 'liptov'
// $cache['rentalType'][144][12] = 'chaty'
// $cache['tag'][144][567] = 'lacne'


class SearchCaching extends \Nette\Object {

	protected $cache;
	protected $country;

	protected $rentalRepositoryAccessor;
	
	public function __construct(\Entity\Location\Location $location) {

		

	}

	public function setCache(Caching\Cache $cache) {

		$this->cache = $cache;

	}

	public function setCriteria($criteria, $value) {

		switch($criteria) {

			case self::CRITERIA_COUNTRY:
				$this->country = $value;
				break;

			default:
				$this->criteria[$this->country] = $this->getIdsFor($criteria, $value);
				break;
		}

	}

	public function inject(\Nette\DI\Container $container) {

		$this->rentalRepositoryAccessor = $container->rentalRepositoryAccessor;

	}

	// private functions
	private function getRentalsFor($criteria, $value) {

		

	}

}