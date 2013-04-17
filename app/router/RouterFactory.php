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
	/**
	 * @var IOwnerRouteListFactory
	 */
	protected $ownerRouteListFactory;

	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;

	public function __construct(array $options, ISimpleRouteFactory $simpleRouteFactory,
								IBaseRouteFactory $baseRouteFactory,
								IFrontRouteFactory $frontRouteFactory,
								IOwnerRouteListFactory $ownerRouteListFactory)
	{
		$this->defaultLanguage = $options['defaultLanguage'];
		$this->defaultPrimaryLocation = $options['defaultPrimaryLocation'];
		$this->baseRouteFactory = $baseRouteFactory;
		$this->simpleRouteFactory = $simpleRouteFactory;
		$this->frontRouteFactory = $frontRouteFactory;
		$this->ownerRouteListFactory = $ownerRouteListFactory;
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function create()
	{
		$this->defaultLanguage = $this->languageRepositoryAccessor->get()->findOneByOldId($this->defaultLanguage);
		$this->defaultPrimaryLocation = $this->locationRepositoryAccessor->get()->findOneByOldId($this->defaultPrimaryLocation);

		$router = new RouteList();


		$mask = '//[!<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,4}>.%domain%/]<module (front|owner|admin)>/<presenter>[/<action>[/<id>]]';
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
