<?php

namespace Routers;

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class ImportRouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function create()
	{

		$router = new RouteList();

		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('index.php', 'Admin:Rental:list', Route::ONE_WAY);
		$adminRouter[] = new Route('admin/<presenter>/<id [0-9]+>', array(
			'presenter' => NULL,
			'action' =>  'edit',
		));
		$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
			'presenter' => NULL,
			'action' =>  'list',
		));

		return $router;
	}

}
