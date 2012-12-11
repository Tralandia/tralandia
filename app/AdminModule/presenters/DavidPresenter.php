<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {

	private $rentalRepositoryAccessor;
	private $locationRepositoryAccessor;
	private $frontRouteFactory;
	private $seoServiceFactory;
	private $robot;

	protected $rentalSearchCachingFactory;
	protected $rentalSearchServiceFactory;

	public function injectRoute(\Routers\IFrontRouteFactory $frontRouteFactory, \Service\Seo\ISeoServiceFactory $seoServiceFactory) {
		$this->frontRouteFactory = $frontRouteFactory;
		$this->seoServiceFactory = $seoServiceFactory;
	}

	public function injectRentalCache(\Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory, \Service\Rental\IRentalSearchServiceFactory $rentalSearchServiceFactory, \Service\Robot\IUpdateRentalSearchKeysCacheRobotFactory $robot) {
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
		$this->rentalSearchServiceFactory = $rentalSearchServiceFactory;
		$this->robot = $robot;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
	}

	public function actionList() {

		//$this->getService('generatePathSegmentsRobot')->run();

		$url = 'http://sk.com.tra.com/';
		$urlScript = new Nette\Http\UrlScript($url);
		$httpRequest = new Nette\Http\Request($urlScript);

		$route = $this->frontRouteFactory->create();

		$request = $route->match($httpRequest);

		$languageRepositoryAccessor = $this->getService('languageRepositoryAccessor');
		$locationRepositoryAccessor = $this->getService('locationRepositoryAccessor');

		d($request);
		$seo = $this->seoServiceFactory->create($url, $request);


	}

	public function actionSearch() {
		$primaryLocation = $this->locationRepositoryAccessor->get()->findOneByIso('sk');
		$location = $this->locationRepositoryAccessor->get()->find(338);

		$this->robot->create($primaryLocation)->run();

		// $thisSearch = $this->rentalSearchServiceFactory->create($primaryLocation);
		// $thisSearch->addLocationCriteria($location);
		// $thisSearch->addTagCriteria($tag);

		// d($thisSearch->getResultsCount());
		// d($thisSearch->getRentalIds());
		// d($thisSearch->getRentals());
		
	}
}