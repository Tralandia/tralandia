<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;
use Nette\Application\Responses\TextResponse;

use Service\Robot\IUpdateRentalSearchKeysCacheRobotFactory;

class RunRobotPresenter extends BasePresenter {

	private $rentalRepositoryAccessor;
	private $locationRepositoryAccessor;

	private $rentalSearchKeysCacheRobotFactory;
	private $rentalSearchCachingFactory;
	
	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	public function injectCache(IUpdateRentalSearchKeysCacheRobotFactory $rentalSearchKeysCacheRobotFactory, \Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory) {
		$this->rentalSearchKeysCacheRobotFactory = $rentalSearchKeysCacheRobotFactory;
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
	}

	public function actionSearchCache() {
		$primaryLocation = $this->locationRepositoryAccessor->get()->findOneByIso('sk');
		//$location = $this->locationRepositoryAccessor->get()->find(338);

		$searchCaching = $this->rentalSearchCachingFactory->create($primaryLocation);
		$searchCaching->drop();
		
		$this->rentalSearchKeysCacheRobotFactory->create($primaryLocation)->run();
		
		//$searchCaching->getOrderList();
		
		$this->sendResponse(new TextResponse('done'));
	}

	public function actionRecalculateRanks() {
		$rentals = $this->rentalRepositoryAccessor->get()->findAll();
		foreach ($rentals as $rental) {
			$rentalDecorator = $this->rentalDecoratorFactory->create($rental);
			$rentalDecorator->calculateRank();
		}		
		$this->sendResponse(new TextResponse('done'));
	}
}