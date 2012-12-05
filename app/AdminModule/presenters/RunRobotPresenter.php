<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;
use Nette\Application\Responses\TextResponse;

use Service\Robot\IUpdateRentalSearchKeysCacheRobotFactory;

class RunRobotPresenter extends BasePresenter {

	private $rentalRepositoryAccessor;
	private $locationRepositoryAccessor;

	private $rentalSearchKeysCacheFactory;


	public function injectCache(IUpdateRentalSearchKeysCacheRobotFactory $rentalSearchKeysCacheFactory) {
		$this->rentalSearchKeysCacheFactory = $rentalSearchKeysCacheFactory;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
	}

	public function actionSearchCache() {
		$primaryLocation = $this->locationRepositoryAccessor->get()->findOneByIso('sk');
		$location = $this->locationRepositoryAccessor->get()->find(338);

		$this->rentalSearchKeysCacheFactory->create($primaryLocation)->run();
		
		$this->sendResponse(new TextResponse('done'));
	}
}