<?php

namespace Routers;

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{
	protected $defaultLanguage;
	protected $defaultPrimaryLocation;

	/**
	 * @var IBaseRouteFactory
	 */
	protected $baseRouteFactory;
	/**
	 * @var ISimpleRouteFactory
	 */
	protected $simpleRouteFactory;
	/**
	 * @var IFrontRouteFactory
	 */
	protected $frontRouteFactory;

	public $languageDao;
	public $locationDao;

	private $domainMask;


	public function __construct($domainMask, array $options, ISimpleRouteFactory $simpleRouteFactory,
								IBaseRouteFactory $baseRouteFactory,
								IFrontRouteFactory $frontRouteFactory)
	{
		$this->defaultLanguage = $options['defaultLanguage'];
		$this->defaultPrimaryLocation = $options['defaultPrimaryLocation'];
		$this->baseRouteFactory = $baseRouteFactory;
		$this->simpleRouteFactory = $simpleRouteFactory;
		$this->frontRouteFactory = $frontRouteFactory;
		$this->domainMask = $domainMask;
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function create()
	{
		$this->defaultLanguage = $this->languageDao->findOneByOldId($this->defaultLanguage);
		$this->defaultPrimaryLocation = $this->locationDao->findOneByOldId($this->defaultPrimaryLocation);

		$router = new RouteList();

		$mask = '//[!' . $this->domainMask . '/]<module (front|owner|admin)>/<presenter>[/<action>[/<id>]]';
		$metadata = [
			BaseRoute::PRIMARY_LOCATION => 'sk',
			BaseRoute::LANGUAGE => 'www',
			'presenter' => 'Home',
			'action' => 'list',
		];

		$router[] = $this->simpleRouteFactory->create($mask, $metadata);

		$router[] = $frontRouter = new RouteList('Front');

		$frontRouter[] = $this->frontRouteFactory->create();


		//$frontRouter[] = $this->frontRouteFactory->create();




		return $router;
	}

}
