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

		$search = $this->rentalSearchFactory->create($this->primaryLocation);
		$featuredIds = $search->getFeaturedRentals(99);

		$rentals = array();
		foreach ($featuredIds as $rental) {
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);
			$rentals[$rental->id]['entity'] = $rental;
		}

		$this->template->rentals = $rentals;
		$this->template->lastSeenRentals = $this->lastSeen->getSeenRentals(12);
		$this->template->isHome = TRUE;
		$this->template->locationRentalsCount = $this->environment->getPrimaryLocation()->getRentalCount();

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this->locationRepository, $this->locationTypeRepository);

	}

}
