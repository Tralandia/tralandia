<?php

namespace AdminModule;

use 
	Extras\Cache\SearchCaching,
	Service\Rental\RentalSearchService;

class CibiPresenter extends BasePresenter {

	public $searchFactory;

	public $locationRepositoryAccessor;
	public $rentalTypeRepositoryAccessor;

	public function inject(\Nette\DI\Container $container) {
		
		$this->locationRepositoryAccessor = $container->locationRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $container->rentalTypeRepositoryAccessor;
	}

	public function injectRentalSearchService(\Service\Rental\IRentalSearchServiceFactory $searchFactory) {
		$this->searchFactory = $searchFactory;
	}

	public function actionList() {

		$location = $this->locationRepositoryAccessor->get()->findOneById(10);

		$search = $this->searchFactory->create($location);
		$search->addCriteria(
			RentalSearchService::CRITERIA_RENTAL_TYPE, 
			$this->rentalTypeRepositoryAccessor->get()->findById(array(5, 6))
		);
		$search->setCountPerPage(50);
		$search->setPage(1);
		$results = $search->getResults();

		dump($results);

	}

}