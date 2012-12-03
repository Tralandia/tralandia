<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {

	private $rentalRepositoryAccessor;
	private $locationRepositoryAccessor;
	private $frontRouteFactory;
	private $seoServiceFactory;

	protected $rentalSearchCachingFactory;

	public function injectRoute(\Routers\IFrontRouteFactory $frontRouteFactory, \Service\Seo\ISeoServiceFactory $seoServiceFactory) {
		$this->frontRouteFactory = $frontRouteFactory;
		$this->seoServiceFactory = $seoServiceFactory;
	}

	public function injectRentalCache(\Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory) {
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
	}

	public function actionList() {

		//$this->getService('generatePathSegmentsRobot')->run();

		$url = 'http://www.sk.tra.com/nitra/golf';
		$url = new Nette\Http\UrlScript($url);
		$httpRequest = new Nette\Http\Request($url);

		$route = $this->frontRouteFactory->create();

		$request = $route->match($httpRequest);

		$languageRepositoryAccessor = $this->getService('languageRepositoryAccessor');
		$locationRepositoryAccessor = $this->getService('locationRepositoryAccessor');

		$seo = $this->seoServiceFactory->create($request);

	}

	public function actionRentalCache() {

		$t = $this->locationRepositoryAccessor->get()->findOneByIso('cz');
		$t = $this->rentalRepositoryAccessor->get()->findFeatured($t);
		d($t);
		//$rental = $this->rentalRepositoryAccessor->get()->find(1);
		//$t = $this->rentalSearchCachingFactory->create($rental);

	}
}