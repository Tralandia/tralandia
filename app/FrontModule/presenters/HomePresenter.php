<?php

namespace FrontModule;
use Model\Rental\IRentalDecoratorFactory;

class HomePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	protected $rentalSearchFactory;

	public function injectSearch(\Service\Rental\IRentalSearchServiceFactory $rentalSearchFactory) {
		$this->rentalSearchFactory = $rentalSearchFactory;
	}

	public function renderDefault() {

		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);
		$featuredIds = $search->getFeaturedRentalIds();

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
