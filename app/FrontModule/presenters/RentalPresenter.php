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

		$this->setLayout('detailLayout');

	}

	public function actionList() {

		$r = $this->rentalRepositoryAccessor->get()->findAll();


		$rentals = $r;

		$this->template->rentals = $rentals;



	}

	//
	// COMPONENTS
	// 


}
