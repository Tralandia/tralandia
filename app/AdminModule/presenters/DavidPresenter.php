<?php

namespace AdminModule;

use Nette;

class DavidPresenter extends BasePresenter {


	public function actionList() {

		$route = $this->context->mainRouteFactory->create();
		
		$this->testRouteIn($route, 'http://dev.tra.com/');

	}

	protected function testRouteIn(Nette\Application\IRouter $route, $url)
	{
		$url = new Nette\Http\UrlScript($url);

		$httpRequest = new Nette\Http\Request($url);

		$request = $route->match($httpRequest);
		d('request', $request);
		$result = $route->constructUrl($request, $url);
		d('result', $result);
	}

	

}
