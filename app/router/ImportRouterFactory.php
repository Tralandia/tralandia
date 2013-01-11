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
		$importRouter = new RouteList('Import');
		$importRouter[] = new Route('index.php', 'Import:Import:default', Route::ONE_WAY);
		$importRouter[] = new Route('import/<presenter>/<id [0-9]+>', array(
			'presenter' => 'Import',
			'action' =>  'default',
		));
		$importRouter[] = new Route('import/<presenter>/[<action>[/<id>]]', array(
			'presenter' => 'Import',
			'action' =>  'default',
		));

		return $importRouter;
	}

}
