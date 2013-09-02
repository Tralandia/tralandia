<?php

namespace FrontModule;
use Model\Rental\IRentalDecoratorFactory;
use Routers\FrontRoute;

class HomePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	/**
	 * @autowire
	 * @var \LastSeen
	 */
	protected $lastSeen;

	/**
	 * @autowire
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchFactory;



	public function renderDefault() {

		if($this->device->isMobile()){
			$this->setView('mobileHome');
			$this->setLayout('layoutMobile');
		}

		$this->template->rentals = $this->getRentals;
//		$this->template->lastSeenRentals = $this->lastSeen->getSeenRentals(12);
		$this->template->isHome = TRUE;
		$this->template->locationRentalsCount = $this->getLocationRentalsCount;
	}

	public function getRentals()
	{
		$search = $this->rentalSearchFactory->create($this->primaryLocation);
		$featuredIds = $search->getFeaturedRentals(99);

		$rentals = array();
		foreach ($featuredIds as $rental) {
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);
			$rentals[$rental->id]['entity'] = $rental;
		}

		return $rentals;
	}

	public function getLocationRentalsCount()
	{
		return $this->environment->getPrimaryLocation()->getRentalCount();
	}

	public function createComponentCountryMap()
	{
		$locationRepository = $this->em->getRepository(LOCATION_ENTITY);
		$locationTypeRepository = $this->em->getRepository(LOCATION_TYPE_ENTITY);
		return new \FrontModule\Components\CountryMap\CountryMap($locationRepository, $locationTypeRepository);

	}

}
