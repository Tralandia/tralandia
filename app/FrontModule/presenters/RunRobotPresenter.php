<?php

namespace FrontModule;

use Nette;
use Nette\Utils\Strings;
use Nette\Application\Responses\TextResponse;

use Service\Robot\IUpdateRentalSearchCacheRobotFactory;

class RunRobotPresenter extends BasePresenter {

	private $locationTypeRepositoryAccessor;

	private $rentalSearchCacheRobotFactory;
	private $rentalSearchCachingFactory;
	private $rentalOrderCachingFactory;

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	public function injectCache(\Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory, \Extras\Cache\IRentalOrderCachingFactory $rentalOrderCachingFactory, IUpdateRentalSearchCacheRobotFactory $rentalSearchCacheRobotFactory) {
		$this->rentalSearchCacheRobotFactory = $rentalSearchCacheRobotFactory;
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
		$this->rentalOrderCachingFactory = $rentalOrderCachingFactory;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $dic->locationTypeRepositoryAccessor;
	}

	public function actionSearchCache() {
		$primaryLocationType = $this->locationTypeRepositoryAccessor->get()->findOneBySlug('country');
		$primaryLocations = $this->locationRepositoryAccessor->get()->findByType($primaryLocationType);
		d(count($primaryLocations));
		foreach ($primaryLocations as $key => $location) {
			$this->rentalSearchCacheRobotFactory->create($location)->run();
			d(Strings::upper($location->iso).': Done');
		}

		//$searchCaching->getOrderList();

		$this->sendResponse(new TextResponse('done'));
	}

	public function actionRecalculateRanks() {
		$rentals = $this->rentalRepositoryAccessor->get()->findAll();
		foreach ($rentals as $rental) {
			$rentalDecorator = $this->rentalDecoratorFactory->create($rental);
			$rentalDecorator->calculateRank();
		}
		$this->rentalRepositoryAccessor->get()->flush();
		$this->sendResponse(new TextResponse('done'));
	}
}
