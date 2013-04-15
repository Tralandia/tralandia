<?php

namespace SearchGenerator;


use Doctrine\ORM\EntityManager;
use Entity\Location\Location;
use Extras\Cache\Cache;
use Service\Rental\IRentalSearchServiceFactory;
use Service\Rental\RentalSearchService;

class SpokenLanguages {

	/**
	 * @var \Entity\Location\Location
	 */
	protected $primaryLocation;


	/**
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchFactory;


	/**
	 * @var \Service\Rental\RentalSearchService
	 */
	protected $search;


	public function __construct(Location $primaryLocation, IRentalSearchServiceFactory $rentalSearchFactory, EntityManager $em) {
		$this->primaryLocation = $primaryLocation;
		$this->rentalSearchFactory = $rentalSearchFactory;

		$this->em = $em;
	}


	/**
	 * @return \Service\Rental\RentalSearchService
	 */
	public function getSearch()
	{
		if (!$this->search) {
			$this->search = $this->rentalSearchFactory->create($this->primaryLocation);
		}

		return $this->search;
	}


	/**
	 * @return array|bool
	 */
	public function getUsed()
	{
		$search = $this->getSearch();

		$languages = $search->getCollectedResults(RentalSearchService::CRITERIA_SPOKEN_LANGUAGE);

		return $this->em->getRepository(LANGUAGE_ENTITY)->findById(array_keys($languages));
	}

}
