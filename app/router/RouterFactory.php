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

		$locationDao = $this->locationDao;
		$languageDao = $this->languageDao;
		$router[] = new Route('//<rentalSlug>.<domainName (uns.sk|uns.local)>/', [
			BaseRoute::PRIMARY_LOCATION => 'sk',
			BaseRoute::LANGUAGE => 'sk',
			'module' => 'PersonalSite',
			'presenter' => 'Default',
			'action' => 'default',
			NULL => [
				Route::FILTER_IN => function(array $params) use ($locationDao, $languageDao) {
					$params[BaseRoute::PRIMARY_LOCATION] = $locationDao->findOneByIso($params[BaseRoute::PRIMARY_LOCATION]);
					$params[BaseRoute::LANGUAGE] = $languageDao->findOneByIso($params[BaseRoute::LANGUAGE]);
					return $params;
				},
				Route::FILTER_OUT => function(array $params) use ($locationDao, $languageDao) {
					$params[BaseRoute::PRIMARY_LOCATION] = $params[BaseRoute::PRIMARY_LOCATION]->getIso();
					$params[BaseRoute::LANGUAGE] = $params[BaseRoute::LANGUAGE]->getIso();
					return $params;
				},
			]
		]);

		$mask = '//[!' . $this->domainMask . '/]<module (front|owner|admin|map)>/<presenter>[/<action>[/<id>]]';
		$metadata = [
			BaseRoute::PRIMARY_LOCATION => 'sk',
			BaseRoute::LANGUAGE => 'www',
			'presenter' => 'Home',
			'action' => 'list',
		];

		$router[] = $this->simpleRouteFactory->create($mask, $metadata);

		$router[] = $frontRouter = new RouteList('Front');

		$frontRouter[] = $this->frontRouteFactory->create();


		return $router;
	}

}
