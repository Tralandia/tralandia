<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;

class RentalPresenter extends BasePresenter {

	public $rentalDecoratorFactory;

	public function injectDecorators(IRentalDecoratorFactory $rentalDecoratorFactory) {
		$this->rentalDecoratorFactory = $rentalDecoratorFactory;
	}


	public function actionDetail($id) {

		$rental = $this->rentalRepositoryAccessor->get()->find($id);

		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}
		
		$rentalService = $this->rentalDecoratorFactory->create($rental);

		$this->template->rental = $rental;
		$this->template->rentalService = $rentalService;
		d($rentalService->getMainPhoto()->getThumbnail());
		d($rentalService->getPhotos());
		$this->setLayout('detailLayout');

	}

	public function actionList() {

		$rentalsEntities = $this->rentalRepositoryAccessor->get()->findAll();	

		$rentals = array();

		foreach ($rentalsEntities as $rental){
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);			
			$rentals[$rental->id]['entity'] = $rental;
		}

		$this->template->rentals = $rentals;

	}

	//
	// COMPONENTS
	// 


}
