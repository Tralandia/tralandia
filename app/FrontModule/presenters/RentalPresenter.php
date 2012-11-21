<?php

namespace FrontModule;

class RentalPresenter extends BasePresenter {

	public $rentalServiceFactory;
	public $rentalAmenityTypeRepositoryAccessor;

	public function setContext(\Nette\DI\Container $dic) {
		parent::setContext($dic);

		$this->setProperty('rentalServiceFactory');
		$this->setProperty('rentalAmenityTypeRepositoryAccessor');
	}


	public function actionDetail($id) {

		$rental = $this->rentalRepositoryAccessor->get()->find($id);

		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}
		
		$rentalService = $this->rentalServiceFactory->create($rental);

		// get amenities by type
		$amenities = $rentalService->getAmenitiesByType(array('bathroom', 'congress'));

		// get locations by type
		$locations = $rentalService->getLocationsByType(array('region'));

		$this->template->rental = $rental;
		$this->template->rentalService = $rentalService;
	}

	public function actionList() {

		

	}

	//
	// COMPONENTS
	// 


}
