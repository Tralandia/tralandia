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
		$location = $this->locationRepositoryAccessor->get()->find(338);

		$this->rentalSearchKeysCacheRobotFactory->create($primaryLocation)->run();
		$this->rentalSearchCachingFactory->create($primaryLocation)->invalidateRentalOrderList();
		$this->sendResponse(new TextResponse('done'));
	}
}