<?php

namespace FrontModule;
use Entity\Rental\Rental;
use Routers\FrontRoute;

class HomePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \VisitedRentals
	 */
	protected $visitedRentals;

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
		$this->template->isRentalFeatured = $this->isRentalFeatured;
	}

	public function getRentals()
	{
		$search = $this->rentalSearchFactory->create($this->primaryLocation);
		$rentalCountOnHome = $this->contextParameters['rentalCountOnHome'];
		$rentals = $search->getFeaturedRentals($rentalCountOnHome);

		return $rentals;
	}


	public function getLocationRentalsCount()
	{
		return $this->environment->getPrimaryLocation()->getRentalCount();
	}

	public function createComponentCountryMap() {

		return new \FrontModule\Components\CountryMap\CountryMap($this->locationRepository, $this->locationTypeRepository);

	}

}
