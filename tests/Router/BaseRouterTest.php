<?php
namespace Tests\Router;

use  Nette, Extras;


/**
 * @backupGlobals disabled
 */
abstract class BaseRouterTest extends \Tests\TestCase
{

	protected function routeInTest(Nette\Application\IRouter $route, $url, $expectedPresenter=NULL, $expectedParams=NULL, $expectedUrl=NULL)
	{
		// ==> $url

		$url = new Nette\Http\UrlScript($url);
		// $url->appendQuery(array(
		// 	'test' => 'testvalue',
		// 	'presenter' => 'querypresenter',
		// ));

		$httpRequest = new Nette\Http\Request($url);

		$request = $route->match($httpRequest);

		if ($request) { // matched
			$params = $request->getParameters();
			//asort($params);
			$this->assertSame( $expectedPresenter, $request->getPresenterName() );
			$this->assertSame( $expectedParams, $params );

			unset($params['extra']);
			$request->setParameters($params);
			$result = $route->constructUrl($request, $url);

			$this->assertSame( $expectedUrl, $result );

		} else { // not matched
			$this->assertNull( $expectedPresenter );
		}
		return $request;
	}



	protected function routeOutTest(Nette\Application\Routers\Route $route, $presenter, $params = array())
	{
		$url = new Nette\Http\Url('http://example.com');
		$request = new Nette\Application\Request($presenter, 'GET', $params);
		return $route->constructUrl($request, $url);
	}
}