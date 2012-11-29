<?php

namespace Extras\Cache;


use Nette\Caching;

// $cache['location'][123] = 'liptov'
// $cache['rentalType'][144][12] = 'chaty'
// $cache['tag'][144][567] = 'lacne'


class SearchCaching extends \Nette\Object {

	protected $primaryLocation;
	protected $criteria;

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

	public function inject(\Nette\DI\Container $container) {
		$this->rentalRepositoryAccessor = $container->rentalRepositoryAccessor;
	}

	public function save($ids) {

	}

}