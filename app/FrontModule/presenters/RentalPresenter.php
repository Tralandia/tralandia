<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;
use FrontModule\Forms\Rental\IReservationFormFactory;

class RentalPresenter extends BasePresenter {

	protected $rentalDecoratorFactory;
	protected $rentalSearchFactory;

	protected $reservationFormFactory;

	public function injectDecorators(IRentalDecoratorFactory $rentalDecoratorFactory) {
		$this->rentalDecoratorFactory = $rentalDecoratorFactory;
	}

	public function injectSearch(\Service\Rental\IRentalSearchServiceFactory $rentalSearchFactory) {
		$this->rentalSearchFactory = $rentalSearchFactory;
	}

	public function injectForm(IReservationFormFactory $reservationFormFactory) {
		$this->reservationFormFactory = $reservationFormFactory;
	}


	public function actionDetail($rental) {
		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}
		
		$rentalService = $this->rentalDecoratorFactory->create($rental);

		$locality = $rental->address->locality;
		$link = $this->link('//list', array('location' => $locality));
		$localitySeo = $this->seoFactory->create($link, $this->getLastCreatedRequest());


		$this->template->rental = $rental;
		$this->template->rentalService = $rentalService;
		$this->template->locality = $localitySeo;

		$this->setLayout('detailLayout');
	}

	public function actionList($primaryLocation, $location, $rentalType) {

		d($rentalType);

		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);

		if ($location) {
			$search->addLocationCriteria($location);
		}

		if ($rentalType) {
			$search->addRentalTypeCriteria($rentalType);
		}

		$vp = $this['paginator'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = \Service\Rental\RentalSearchService::COUNT_PER_PAGE;
		$paginator->itemCount = $search->getRentalsCount();	
		$paginator->itemCount = 123;

		$rentalsEntities = $search->getRentals(0);//@todo
		$rentals = array();

		foreach ($rentalsEntities as $rental) {
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);			
			$rentals[$rental->id]['entity'] = $rental;
		}

		$this->template->rentals = $rentals;

	}

	//
	// COMPONENTS
	// 


	protected function createComponentReservationForm()
	{
		$form = $this->reservationFormFactory->create($this->getParameter('rental'));
		//$form->buildForm();
	
		$form->onSuccess[] = function ($form) { 
			if ($form->valid) $form->presenter->redirect('this'); 
		};
	
		return $form;
	}

	protected function createComponentPaginator() {
		$vp = new \VisualPaginator();
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';
		return $vp;
	}
}
