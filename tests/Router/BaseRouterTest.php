<?php
namespace Tests\Router;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
abstract class BaseRouterTest extends \Tests\TestCase
{

	protected function routeIn(Nette\Application\IRouter $route, $url, $expectedPresenter=NULL, $expectedParams=NULL, $expectedUrl=NULL)
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
			//d($request); #@debug
			$this->assertSame( $expectedPresenter, $request->getPresenterName() );
			foreach($params as $paramName => $param) {
				if(!array_key_exists($paramName, $expectedParams)) {
					continue;
				}
				if($param instanceof \Entity\BaseEntity) {
					$param = $param->getOldId();
				}
				$this->assertSame( $expectedParams[$paramName], $param );
			}

			//unset($params['extra']);
			$request->setParameters($params);
			$result = $route->constructUrl($request, $url);

			$this->assertSame( $expectedUrl, $result );

		} else { // not matched
			$this->assertNull( $expectedPresenter );
		}
		return $request;
	}



	protected function routeOut(Nette\Application\Routers\Route $route, $presenter, $params = array())
	{
		$url = new Nette\Http\Url('http://example.com');
		$request = new Nette\Application\Request($presenter, 'GET', $params);
		return $route->constructUrl($request, $url);
	}
}