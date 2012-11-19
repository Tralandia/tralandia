<?php

namespace FrontModule;

class RentalPresenter extends BasePresenter {

	public $rentalServiceFactory;

	public function setContext(\Nette\DI\Container $dic) {
		parent::setContext($dic);

		$this->setProperty('rentalServiceFactory');
	}


	public function actionDetail($id) {

		$rental = $this->rentalRepositoryAccessor->get()->find($id);

		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}
		
		$rentalService = $this->rentalServiceFactory->create($rental);

		$this->template->rental = $rental;
		$this->template->rentalService = $rentalService;

		$this->setLayout('detailLayout');

	}

	public function actionList() {

		$rentals = $this->rentalRepositoryAccessor->get()->findAll();

		$this->template->rentals = $rentals;



	}

	//
	// COMPONENTS
	// 


}
