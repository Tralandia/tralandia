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
	protected $defaultPrymaryLocation;
	protected $frontRouteFactory;

	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;

	public function __construct(array $options, IFrontRouteFactory $frontRouteFactory) {
		$this->defaultLanguage = $options['defaultLanguage'];
		$this->defaultPrymaryLocation = $options['defaultPrimaryLocation'];
		$this->frontRouteFactory = $frontRouteFactory;
	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function create()
	{
		$this->defaultLanguage = $this->languageRepositoryAccessor->get()->find($this->defaultLanguage);
		$this->defaultPrymaryLocation = $this->locationRepositoryAccessor->get()->find($this->defaultPrymaryLocation);

		$router = new RouteList();

		$router[] = $gregor = new RouteList('gregor');
		$gregor[] = new Route('gregor/[<presenter>/[<action>[/<id>]]]', array(
			'presenter' => 'Page',
			'action' =>  'home',
			'primaryLocation' => $this->defaultPrymaryLocation,
			'language' => $this->defaultLanguage,			
		));

		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('index.php', 'Admin:Rental:list', Route::ONE_WAY);
		$adminRouter[] = new Route('admin/<presenter>/<id [0-9]+>', array(
			'presenter' => NULL,
			'action' =>  'edit',
			'primaryLocation' => $this->defaultPrymaryLocation,
			'language' => $this->defaultLanguage,
		));
		$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
			'presenter' => NULL,
			'action' =>  'list',
			'primaryLocation' => $this->defaultPrymaryLocation,
			'language' => $this->defaultLanguage,
		));
	/*	$adminRouter[] = new Route('admin/<presenter>/[<action list|add|registration>]', array(
			'presenter' => 'Admin',
			'action' =>  'list'
		));
	*/	
		$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
			'presenter' => 'Admin',
			'action' =>  'list',
			'primaryLocation' => $this->defaultPrymaryLocation,
			'language' => $this->defaultLanguage,
		));

		$router[] = $ownerRouter = new RouteList('Owner');

		$ownerRouter[] = new Route('owner/<presenter>/[<action>[/<id>]]', array(
			'presenter' => 'Rental',
			'action' =>  'default',
			'primaryLocation' => $this->defaultPrymaryLocation,
			'language' => $this->defaultLanguage,
		));

		
		$router[] = $frontRouter = new RouteList('Front');

		//$frontRouter[] = $this->frontRouteFactory->create();
	
		
		$frontRouter[] = new Route('<presenter>/[<action>[/<id>]]', array(
			'presenter' => 'Home',
			'action' =>  'default',
			'primaryLocation' => $this->defaultPrymaryLocation,
			'language' => $this->defaultLanguage,
		));

		return $router;
	}

}
