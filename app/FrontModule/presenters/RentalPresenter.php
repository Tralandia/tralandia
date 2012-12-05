<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;

class RentalPresenter extends BasePresenter {

	public $rentalDecoratorFactory;
	public $rentalSearchFactory;

	public function injectDecorators(IRentalDecoratorFactory $rentalDecoratorFactory) {
		$this->rentalDecoratorFactory = $rentalDecoratorFactory;
	}

	public function injectSearch(\Service\Rental\IRentalSearchServiceFactory $rentalSearchFactory) {
		$this->rentalSearchFactory = $rentalSearchFactory;
	}


	public function actionDetail($rental) {
		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}
		
		$rentalService = $this->rentalDecoratorFactory->create($rental);

		$locality = $rentalService->getLocationsByType('locality', 1);
		$locality = reset($locality);
		$link = $this->link('//list', array('location' => $locality));
		
		$this->template->rental = $rental;
		$this->template->rentalService = $rentalService;
		$this->template->locality = $this->seoFactory->create($link);

		$this->setLayout('detailLayout');
	}

	public function actionList($primaryLocation, $location) {

		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);
		$search->addLocationCriteria($location);

		$vp = $this['paginator'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = \Service\Rental\RentalSearchService::COUNT_PER_PAGE;
		//$paginator->itemCount = $search->getRentalsCount();	
		$paginator->itemCount = 123;

		$rentalsEntities = $search->getRentals(0);//@todo
		$rentals = array();

		foreach ($rentalsEntities as $rental) {
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

	protected function createComponentPaginator() {
		$vp = new \VisualPaginator();
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';
		return $vp;
	}
}
