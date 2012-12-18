<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;

class HomePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;


	public function renderDefault() {

		$rentalsEntities = $this->rentalRepositoryAccessor->get()->findAll();	

		$rentals = array();

		foreach ($rentalsEntities as $rental){
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);			
			$rentals[$rental->id]['entity'] = $rental;
		}

		$this->template->rentals = $rentals;

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this->locationRepository, $this->locationTypeRepository);

	}

}
