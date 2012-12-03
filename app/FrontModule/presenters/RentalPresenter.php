<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;

class RentalPresenter extends BasePresenter {

	public $rentalDecoratorFactory;

	public function injectDecorators(IRentalDecoratorFactory $rentalDecoratorFactory) {
		$this->rentalDecoratorFactory = $rentalDecoratorFactory;
	}


	public function actionDetail($rental) {
		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}
		
		$rentalService = $this->rentalDecoratorFactory->create($rental);
		$this->template->rental = $rental;
		d($rental->maxCapacity);
		$this->template->rentalService = $rentalService;
		//d($rental->locations->getIterator());
		$this->setLayout('detailLayout');

	}

	public function actionList() {

		$rentalsEntities = $this->rentalRepositoryAccessor->get()->findAll();	

		$rentals = array();

		foreach ($rentalsEntities as $rental){
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);			
			$rentals[$rental->id]['entity'] = $rental;
		}

		

		$regions = $this->locationRepositoryAccessor->get()->findBy(array(
				'parent' => 58
			), null , 50);


		$topRegions = $this->locationRepositoryAccessor->get()->findBy(array(
				'parent' => 58
			), null , 11);

		$this->template->rentals = $rentals;
		$this->template->regions = array_chunk($regions,ceil(count($regions)/3));
		$this->template->topRegions = array_chunk($topRegions,ceil(count($topRegions)/3));

	}

	//
	// COMPONENTS
	// 


}
