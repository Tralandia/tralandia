<?php

namespace AdminModule;

use Nette;

class DavidPresenter extends BasePresenter {


	public function actionList() {
		// $this->context->generatePathSegmentsRobot->run();

		$route = $this->context->mainRouteFactory->create();
		
		$this->testRouteIn($route, 'http://www.tra.sk/nitra');
		$this->testRouteIn($route, 'http://www.tra.sk/pozicovna');
		$this->testRouteIn($route, 'http://www.tra.sk/zabavny-park');

	}

	protected function testRouteIn(Nette\Application\IRouter $route, $url)
	{
		$url = new Nette\Http\UrlScript($url);

		$httpRequest = new Nette\Http\Request($url);

		$request = $route->match($httpRequest);
		$result = $route->constructUrl($request, $url);
		d("$url", $request, $result);
	}

	

}
