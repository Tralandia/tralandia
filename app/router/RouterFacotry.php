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

	public function __construct(MainRouteFactory $mainRoute) {

	}

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function create()
	{
		$router = new RouteList();

		$router[] = $gregor = new RouteList('gregor');
		$gregor[] = new Route('gregor/[<presenter>/[<action>[/<id>]]]', array(
			'presenter' => 'Page',
			'action' =>  'home'
		));

		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('index.php', 'Admin:Rental:list', Route::ONE_WAY);
		$adminRouter[] = new Route('admin/<presenter>/<id [0-9]+>', array(
			'presenter' => NULL,
			'action' =>  'edit'
		));
		$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
			'presenter' => NULL,
			'action' =>  'list'
		));
	/*	$adminRouter[] = new Route('admin/<presenter>/[<action list|add|registration>]', array(
			'presenter' => 'Admin',
			'action' =>  'list'
		));
	*/	$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
			'presenter' => 'Admin',
			'action' =>  'list'
		));

		$router[] = $frontRouter = new RouteList('Front');
	/*	$frontRouter[] = new \Extras\Route($container->routerCache, array(
									'presenter' => 'David',
									'action' => 'default',
									'country' => 'SK',
								));
	*/
		
		$frontRouter[] = new Route('<presenter>/[<action>[/<id>]]', 'Home:default');

		return $router;
	}

}
