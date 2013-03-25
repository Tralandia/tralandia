<?php

namespace SearchGenerator;


use Doctrine\ORM\EntityManager;
use Entity\Location\Location;
use Extras\Cache\Cache;
use Service\Rental\RentalSearchService;

class SpokenLanguages {

	/**
	 * @var mixed|NULL
	 */
	protected $cacheData;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;


	/**
	 * @param \Entity\Location\Location $primaryLocation
	 * @param \Extras\Cache\Cache $rentalSearchCache
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(Location $primaryLocation, Cache $rentalSearchCache, EntityManager $em) {
		$this->cacheData = $rentalSearchCache->load($primaryLocation->getId());
		$this->em = $em;
	}


	/**
	 * @return array
	 */
	public function getUsed()
	{
		$languagesIds = array_keys($this->cacheData[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE]);
		return $this->em->getRepository(LANGUAGE_ENTITY)->findById($languagesIds);
	}

}
