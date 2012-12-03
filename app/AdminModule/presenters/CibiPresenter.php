<?php

namespace AdminModule;

use 
	Extras\Cache\SearchCaching,
	Service\Rental\RentalSearchService;

class CibiPresenter extends BasePresenter {

	public $searchFactory;

	public $locationRepositoryAccessor;
	public $rentalTypeRepositoryAccessor;
	public $rentalAmenityRepositoryAccessor;
	public $languageRepositoryAccessor;

	public function inject(\Nette\DI\Container $container) {
		
		$this->locationRepositoryAccessor = $container->locationRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $container->rentalTypeRepositoryAccessor;
		$this->rentalAmenityRepositoryAccessor = $container->rentalAmenityRepositoryAccessor;
		$this->languageRepositoryAccessor = $container->languageRepositoryAccessor;
	}

	public function injectRentalSearchService(\Service\Rental\IRentalSearchServiceFactory $searchFactory) {
		$this->searchFactory = $searchFactory;
	}

	public function actionList() {

		$location = $this->locationRepositoryAccessor->get()->findOneById(10);

		$search = $this->searchFactory->create($location);
		$search->addCriteria(
			RentalSearchService::CRITERIA_RENTAL_TYPE, 
			$this->rentalTypeRepositoryAccessor->get()->findById(array(2, 10, 6))
		);
		$search->addCriteria(
			RentalSearchService::CRITERIA_LOCATION, 
			$this->locationRepositoryAccessor->get()->findById(58)
		);
		$search->addCriteria(
			RentalSearchService::CRITERIA_AMENITIES, 
			$this->rentalAmenityRepositoryAccessor->get()->findById(array(139, 140))
		);
		$search->addCriteria(
			RentalSearchService::CRITERIA_LANGUAGES_SPOKEN, 
			$this->languageRepositoryAccessor->get()->findById(array(28, 38))
		);
		$search->addCriteria(
			RentalSearchService::CRITERIA_CAPACITY, 
			10
		);
		$search->setCountPerPage(50);
		$search->setPage(1);
		$results = $search->getRentals();

		dump($results);

	}

}