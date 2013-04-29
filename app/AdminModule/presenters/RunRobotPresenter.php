<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;
use Nette\Application\Responses\TextResponse;

use Robot\IUpdateRentalSearchCacheRobotFactory;

class RunRobotPresenter extends BasePresenter {

	private $locationTypeRepositoryAccessor;

	/**
	 * @autowire
	 * @var \Robot\IUpdateRentalSearchCacheRobotFactory
	 */
	protected $rentalSearchCacheRobotFactory;

	/**
	 * @autowire
	 * @var \Robot\UpdateTranslationStatusRobot
	 */
	protected $updateTranslationStatusRobot;

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	public function inject(\Nette\DI\Container $dic) {
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $dic->locationTypeRepositoryAccessor;
	}

	public function actionSearchCache() {
		$primaryLocationType = $this->locationTypeRepositoryAccessor->get()->findOneBySlug('country');
		$primaryLocations = $this->locationRepositoryAccessor->get()->findByType($primaryLocationType);
		//$primaryLocations = $this->locationRepositoryAccessor->get()->findById(159);
		d('Idem importovat ' . count($primaryLocations) . ' krajin.');
		foreach ($primaryLocations as $key => $location) {
			$this->rentalSearchCacheRobotFactory->create($location)->run();
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

	public function actionUpdateTranslationStatus()
	{
		$robot = $this->updateTranslationStatusRobot;

		if($robot->needToRun()) {
			$robot->run();
		}

		$this->redirect(':Admin:Currency:list');
	}
}
