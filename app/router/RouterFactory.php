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
	protected $frontRouteFactory;
	protected $ownerRouteListFactory;

	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;

	public function __construct(array $options, IFrontRouteFactory $frontRouteFactory, IOwnerRouteListFactory $ownerRouteListFactory) {
		$this->defaultLanguage = $options['defaultLanguage'];
		$this->defaultPrimaryLocation = $options['defaultPrimaryLocation'];
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
		$gregor[] = new Route('gregor/[<presenter>/[<action>[/<id>]]]', array(
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
		$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
			'presenter' => NULL,
			'action' =>  'list',
			'primaryLocation' => $this->defaultPrimaryLocation,
			'language' => $this->defaultLanguage,
		));
	/*	$adminRouter[] = new Route('admin/<presenter>/[<action list|add|registration>]', array(
			'presenter' => 'Admin',
			'action' =>  'list'
		));
	*/

		$router[] = $ownerRouter = $this->ownerRouteListFactory->create();
		
		$router[] = $frontRouter = new RouteList('Front');

		$frontRouter[] = $this->frontRouteFactory->create();

	
		
		$frontRouter[] = new Route('<presenter>/[<action>[/<id>]]', array(
			'action' =>  'default',
			'primaryLocation' => $this->defaultPrimaryLocation,
			'language' => $this->defaultLanguage,
		));

		return $router;
	}

}
