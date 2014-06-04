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
	 * @var ISimpleRouteFactory
	 */
	protected $simpleRouteFactory;
	/**
	 * @var IFrontRouteFactory
	 */
	protected $frontRouteFactory;

	/**
	 * @var ISimpleFrontRouteFactory
	 */
	protected $simpleFrontRouteFactory;

	public $languageDao;
	public $locationDao;

	private $domainMask;

	/**
	 * @var IPersonalSiteRouteFactory
	 */
	private $personalSiteRouteFactory;

	/**
	 * @var ICustomPersonalSiteRouteFactory
	 */
	private $customPersonalSiteRouteFactory;

	/**
	 * @var array
	 */
	private $options;


	public function __construct($domainMask, array $options, ISimpleRouteFactory $simpleRouteFactory,
								IFrontRouteFactory $frontRouteFactory, ISimpleFrontRouteFactory $simpleFrontRouteFactory,
								IPersonalSiteRouteFactory $personalSiteRouteFactory,
								ICustomPersonalSiteRouteFactory $customPersonalSiteRouteFactory)
	{
		$this->defaultLanguage = $options['defaultLanguage'];
		$this->defaultPrimaryLocation = $options['defaultPrimaryLocation'];
		$this->simpleRouteFactory = $simpleRouteFactory;
		$this->frontRouteFactory = $frontRouteFactory;
		$this->simpleFrontRouteFactory = $simpleFrontRouteFactory;
		$this->personalSiteRouteFactory = $personalSiteRouteFactory;
		$this->customPersonalSiteRouteFactory = $customPersonalSiteRouteFactory;
		$this->domainMask = $domainMask;
		$this->options = $options;
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function create()
	{
		$this->defaultLanguage = $this->languageDao->findOneByOldId($this->defaultLanguage);
		$this->defaultPrimaryLocation = $this->locationDao->findOneByOldId($this->defaultPrimaryLocation);

		$router = new RouteList();

		$router[] = $personalSite = new RouteList('PersonalSite');

		$personalSite[] = $this->customPersonalSiteRouteFactory->create('//<host (?:(?!tralandia|tra-local)[a-z0-9\\.\\-])+>/[!<language [a-z]{2}>]', [
			'presenter' => 'Default',
			'action' => 'default'
		]);

		$personalSite[] = $this->personalSiteRouteFactory->create('//[!<www www.>]<rentalSlug [a-z0-9-]{4,}>.<host [a-z\\.\\-]+>/[!<language [a-z]{2}>]', [
			'presenter' => 'Default',
			'action' => 'default'
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

		$frontRouter[] = $this->simpleFrontRouteFactory->create();
//		$frontRouter[] = $this->frontRouteFactory->create();


		return $router;
	}

}
