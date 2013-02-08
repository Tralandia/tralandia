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
	 * @var IOwnerRouteListFactory
	 */
	protected $ownerRouteListFactory;

	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;

	public function __construct(array $options, ISimpleRouteFactory $simpleRouteFactory,
								IFrontRouteFactory $frontRouteFactory,
								IOwnerRouteListFactory $ownerRouteListFactory)
	{
		$this->defaultLanguage = $options['defaultLanguage'];
		$this->defaultPrimaryLocation = $options['defaultPrimaryLocation'];
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

		$router[] = $gregor = new RouteList('gregor');
		$gregor[] = new Route('gregor[/<presenter>[/<action>[/<id>]]]', array(
			'presenter' => 'Page',
			'action' =>  'home',
			'primaryLocation' => $this->defaultPrimaryLocation,
			'language' => $this->defaultLanguage,			
		));

		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('index.php', 'Admin:Rental:list', Route::ONE_WAY);
		$adminRouter[] = new Route('admin/<presenter>/<id [0-9]+>', array(
			'presenter' => NULL,
			'action' =>  'edit',
			'primaryLocation' => $this->defaultPrimaryLocation,
			'language' => $this->defaultLanguage,
		));
		$adminRouter[] = new Route('admin/<presenter>[/<action>[/<id>]]', array(
			'presenter' => NULL,
			'action' =>  'list',
			'primaryLocation' => $this->defaultPrimaryLocation,
			'language' => $this->defaultLanguage,
		));
	/*	$adminRouter[] = new Route('admin/<presenter>[/<action list|add|registration>]', array(
			'presenter' => 'Admin',
			'action' =>  'list'
		));
	*/

		$router[] = $this->ownerRouteListFactory->create();


		$router[] = $frontRouter = new RouteList('Front');

		$mask = '//[!<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,3}>.%domain%/]<presenter>[/<action>[/<id>]]';
		$metadata = [
			BaseRoute::PRIMARY_LOCATION => 'sk',
			BaseRoute::LANGUAGE => 'www',
			'presenter' => 'Home',
			'action' => 'default',
		];
		$frontRouter[] = $this->simpleRouteFactory->create($mask, $metadata);

		$mask = '//[!<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,3}>.%domain%/][<hash .*>]';
		$metadata = [
			BaseRoute::PRIMARY_LOCATION => 'sk',
			BaseRoute::LANGUAGE => 'www',
			'presenter' => 'Rental',
			'action' => 'list',
		];
		$frontRouter[] = $this->frontRouteFactory->create($mask, $metadata);
		//$frontRouter[] = $this->frontRouteFactory->create();




		return $router;
	}

}
