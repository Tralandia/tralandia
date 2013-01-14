<?php

namespace FrontModule;
use Model\Rental\IRentalDecoratorFactory;

class HomePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	/**
	 * @autowire
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchFactory;




	public function renderDefault() {
		
		$this->template->countryCountObjects = $this->environment->primaryLocation->rentalCount;

		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);

		$featuredIds = $search->getFeaturedRentals();
	
		$rentals = array();

		foreach ($featuredIds as $rental) {
		 	$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);			
		 	$rentals[$rental->id]['entity'] = $rental;
		}

		$this->template->rentals = $rentals;
	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this->locationRepository, $this->locationTypeRepository);

	}

}
