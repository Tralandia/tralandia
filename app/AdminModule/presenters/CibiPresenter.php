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
	public $userSiteOwnerReviewRepositoryAccessor;

	public function inject(\Nette\DI\Container $container) {
		
		$this->locationRepositoryAccessor = $container->locationRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $container->rentalTypeRepositoryAccessor;
		$this->rentalAmenityRepositoryAccessor = $container->rentalAmenityRepositoryAccessor;
		$this->languageRepositoryAccessor = $container->languageRepositoryAccessor;
		$this->userSiteOwnerReviewRepositoryAccessor = $container->userSiteOwnerReviewRepositoryAccessor;
	}

	public function injectRentalSearchService(\Service\Rental\IRentalSearchServiceFactory $searchFactory) {
		$this->searchFactory = $searchFactory;
	}

	public function actionList() {

		$this->template->review = $this->userSiteOwnerReviewRepositoryAccessor->get()->findOneById(3);

	}

}