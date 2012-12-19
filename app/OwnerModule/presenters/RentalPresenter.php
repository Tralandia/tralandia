<?php

namespace OwnerModule;

use Model\Rental\IRentalDecoratorFactory;
use FrontModule\Forms\Rental\IReservationFormFactory;

class RentalPresenter extends BasePresenter {

	public $rentalRepositoryAccessor;

	protected $rental;

	protected $formFactory;

	protected $rentalDecoratorFactory;
	protected $rentalSearchFactory;
	protected $reservationFormFactory;


	public function injectDecorators(IRentalDecoratorFactory $rentalDecoratorFactory) {
		$this->rentalDecoratorFactory = $rentalDecoratorFactory;
	}

	public function injectSearch(\Service\Rental\IRentalSearchServiceFactory $rentalSearchFactory) {
		$this->rentalSearchFactory = $rentalSearchFactory;
	}	

	public function injectForm(Forms\IRentalEditFormFactory $formFactory ){
		$this->formFactory = $formFactory;
	}

	public function injectDic(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function actionAdd(){
		
	}

	public function actionEdit($id) {

		$this->rental = $this->rentalRepositoryAccessor->get()->find($id);		

		//$rentalService = $this->rentalDecoratorFactory->create($this->rental);

		$this->template->rental = $this->rental;
		//$this->template->rentalService = $rentalService;

	}

	protected function createComponentRentalEditForm(){
		$form = $this->formFactory->create($this->rental);
		$form->buildForm();
		return $form ;
	}

	public function actionList($primaryLocation, $location) {

		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);


		$this->rental = $this->rentalRepositoryAccessor->get()->findAll(1);
		d($this->rental);

		 $rentalsEntities = $search->getRentals(0);//@todo
		$rentals = array();

		foreach ($this->rental as $rental) {
		 	$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);			
		 	$rentals[$rental->id]['entity'] = $rental;
		 }

		//$this->template->rental = $this->rental;
		$this->template->rentals = $rentals;

	}	

}
