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
	 * @var \Dictionary\UpdateTranslationStatus
	 */
	protected $updateTranslationStatus;

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
	
	protected $rentalRepositoryAccessor;

	public function inject(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
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
		$rentals = $this->rentalRepositoryAccessor->get()->findBy(array('status' => NULL), null, 50);
		$i = 0;
		foreach ($rentals as $rental) {
			$rentalDecorator = $this->rentalDecoratorFactory->create($rental);
			$rentalDecorator->calculateRank();
			// $i++;
			// if (($i % 50) == 0) {
			// 	$this->rentalRepositoryAccessor->get()->flush();
			// 	$rentals = $this->rentalRepositoryAccessor->get()->findBy(array('rank' => NULL), null, 50);
			// 	$i=0;
			// }
		}
		$this->rentalRepositoryAccessor->get()->flush();
		$script = '<br>Working...';
		$script .= '<script>document.location.href="/admin/run-robot/recalculate-ranks"</script>';
		$this->sendResponse(new \Nette\Application\Responses\TextResponse($script));
		// $this->sendResponse(new TextResponse('done'));
	}

	public function actionUpdateTranslationStatus($iteration = 1)
	{
		$robot = $this->updateTranslationStatusRobot;
		$robot->setCurrentIteration($iteration);

		$robot->run();

		if($robot->needToRun()) {
			$nextIteration = $robot->getNextIteration();
			$nextLink = $this->link('this', ['iteration' => $nextIteration]);
			$this->template->nextLink = $nextLink;
		}

	}

	public function actionTest($id)
	{
		$phrase = $this->em->getRepository(PHRASE_ENTITY)->find($id);
		$this->updateTranslationStatus->resolvePhrase($phrase);
	}
}
