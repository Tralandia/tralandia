<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;

class HomePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	// public function injectDecorators(IRentalDecoratorFactory $rentalDecoratorFactory) {
	// 	$this->rentalDecoratorFactory = $rentalDecoratorFactory;
	// }


	public function renderDefault() {

		d($this->rentalDecoratorFactory); #@debug

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
