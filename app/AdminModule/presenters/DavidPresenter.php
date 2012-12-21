<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {

	private $frontRouteFactory;
	private $seoServiceFactory;
	private $robot;

	protected $rentalRepositoryAccessor;
	protected $rentalImageRepositoryAccessor;
	protected $locationRepositoryAccessor;
	protected $rentalPricelistRepositoryAccessor;

	protected $rentalSearchCachingFactory;
	protected $rentalSearchServiceFactory;

	protected $rentalImageDecoratorFactory;

	/**
	 * @autowire
	 * @var Model\Rental\IPricelistDecoratorFactory
	 */
	protected $pricelistDecoratorFactory;


	public function injectRoute(\Routers\IFrontRouteFactory $frontRouteFactory, \Service\Seo\ISeoServiceFactory $seoServiceFactory) {
		$this->frontRouteFactory = $frontRouteFactory;
		$this->seoServiceFactory = $seoServiceFactory;
	}

	public function injectRentalCache(\Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory, \Service\Rental\IRentalSearchServiceFactory $rentalSearchServiceFactory, \Service\Robot\IUpdateRentalSearchKeysCacheRobotFactory $robot) {
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
		$this->rentalSearchServiceFactory = $rentalSearchServiceFactory;
		$this->robot = $robot;
	}

	public function injectDic(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->rentalImageRepositoryAccessor = $dic->rentalImageRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->rentalPricelistRepositoryAccessor = $dic->rentalPricelistRepositoryAccessor;
	}

	public function inject(\Model\Rental\IImageDecoratorFactory $rentalImageDecoratorFactory) {
		$this->rentalImageDecoratorFactory = $rentalImageDecoratorFactory;
	}

	public function actionList() {

		$pricelist = $this->rentalPricelistRepositoryAccessor->get()->createNew();
		$pricelistDecorator = $this->pricelistDecoratorFactory->create($pricelist);
		$pricelistDecorator->setContentFromFile('http://www.tralandia.sk/u/01/13220628967186.png');
		d($pricelistDecorator); #@debug
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

	public function actionRouter()
	{

		d($this->link(':Front:Sign:in')); #@debug
		$this->terminate();

		$url = 'http://www.sk.tra.com/sign/in';
		$urlScript = new Nette\Http\UrlScript($url);
		// d($urlScript); #@debug
		$httpRequest = new Nette\Http\Request($urlScript);

		$route = $this->getService('ownerRouteListFactory')->create();

		$request = $route->match($httpRequest);
		d($request); #@debug

		$url = $route->constructUrl($request, $urlScript);
		d($url); #@debug

	}
}