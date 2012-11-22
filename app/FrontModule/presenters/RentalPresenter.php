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
		d($rentalService->getPhotos());
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
