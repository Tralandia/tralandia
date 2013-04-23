<?php
namespace Tests\Router;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
abstract class BaseRouterTest extends \Tests\TestCase
{

	protected function routeIn(Nette\Application\IRouter $route, $url, $expectedPresenter=NULL, $expectedParams=NULL, $expectedUrl=TRUE)
	{
		// ==> $url

		if($expectedUrl === TRUE) {
			$expectedUrl = $url;
		}

		$url = new Nette\Http\UrlScript($url);
		// $url->appendQuery(array(
		// 	'test' => 'testvalue',
		// 	'presenter' => 'querypresenter',
		// ));

		$httpRequest = new Nette\Http\Request($url);

		$request = $route->match($httpRequest);

		if ($request) { // matched
			$params = $request->getParameters();

			$this->assertSame($expectedPresenter, $request->getPresenterName(), 'Nazov prezenteru sa nezhoduje!');

			foreach($params as $paramName => $param) {
				$this->assertArrayHasKey($paramName, $expectedParams);
				if($expectedParams[$paramName] instanceof BaseEntity) {
					$this->assertEntities($expectedParams[$paramName], $param);
				} else {
					$this->assertSame($expectedParams[$paramName], $param);
				}
			}

			foreach($expectedParams as $paramName => $value) {
				$this->assertArrayHasKey($paramName, $params);
				if($value instanceof BaseEntity) {
					$this->assertEntities($value, $params[$paramName]);
				} else {
					$this->assertSame($value, $params[$paramName]);
				}
			}

			//unset($params['extra']);
			//$request->setParameters($params);
			$result = $route->constructUrl($request, $url);

			$this->assertEquals( $expectedUrl, $result );

		} else { // not matched
			$this->assertNull( $expectedPresenter );
		}
		return $request;
	}



	protected function routeOut(Nette\Application\IRouter $route, $presenter, $params = array(), $expectUrl = NULL)
	{
		$url = new Nette\Http\Url('http://example.com');
		$request = new Nette\Application\Request($presenter, 'GET', $params);
		$url = $route->constructUrl($request, $url);

		$this->assertEquals($expectUrl, $url);
	}

	public function assertEntities(BaseEntity $expected,BaseEntity $actual, $message = NULL)
	{
		$this->assertSame($expected->getClass(), $actual->getClass(), $message);
		$this->assertSame($expected->getId(), $actual->getId(), $message);
	}

}
