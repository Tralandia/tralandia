<?php
namespace Tests\Router;

use Nette, Extras;
use Routers\BaseRoute;
use Routers\FrontRoute;


/**
 * @backupGlobals disabled
 */
class RouterSpeedTest extends BaseRouterTest
{
	public $frontRoute;
	public $simpleFrontRoute;

	public $urls = [];
	public $requests = [];

	protected function setUp() {
		$this->frontRoute = $this->getContext()->frontRouteFactory->create();
		$this->simpleFrontRoute = $this->getContext()->simpleFrontRouteFactory->create();

		$this->requests = include 'requests.php';
	}


	// ~0.12
	public function testFrontRoute()
	{
		$requests = [];
		foreach($this->requests as $request) {
			$params = [];

			$presenter = $request['presenter'];
			$params['action'] = $request['action'];
			unset($request['presenter'], $request['action']);
			foreach($request as $key => $value) {
				if($key === FrontRoute::LANGUAGE) {
					$value = $this->findLanguage($value);
				} else if($key === FrontRoute::PRIMARY_LOCATION) {
					$value = $this->findLocation($value);
				} else if($key === FrontRoute::LOCATION) {
					$value = $this->findLocation($value);
				} else if($key === FrontRoute::RENTAL_TYPE) {
					$value = $this->findRentalType($value);
				}

				if(array_key_exists($key, FrontRoute::$pathParametersMapper)) {
					$params[FrontRoute::$pathParametersMapper[$key]] = $value;
				} else {
					$params[$key] = $value;
				}
			}
			$requests[] = [
				'presenter' => $presenter,
				'params' => $params,
			];
		}

		lt('frontRoute', 'lt-SimpleRouterTest');
		foreach($requests as $request) {
			$url = $this->getUrl($this->frontRoute, $request['presenter'], $request['params']);
			$this->assertNotNull($url);
		}
		lt('frontRoute', 'lt-SimpleRouterTest');
	}

	public function testSimpleFrontRoute()
	{
		$requests = [];
		foreach($this->requests as $request) {
			$params = [];

			$presenter = $request['presenter'];
			$params['action'] = $request['action'];
			unset($request['presenter'], $request['action']);
			foreach($request as $key => $value) {
				if($key === FrontRoute::LANGUAGE) {
					$value = $this->findLanguage($value);
				} else if($key === FrontRoute::PRIMARY_LOCATION) {
					$value = $this->findLocation($value);
				}

				if(array_key_exists($key, FrontRoute::$pathParametersMapper)) {
					$params[FrontRoute::$pathParametersMapper[$key]] = $value;
				} else {
					$params[$key] = $value;
				}
			}
			$requests[] = [
				'presenter' => $presenter,
				'params' => $params,
			];
		}

		lt('simpleFrontRoute', 'lt-SimpleRouterTest');
		foreach($requests as $request) {
			$url = $this->getUrl($this->simpleFrontRoute, $request['presenter'], $request['params']);
			$this->assertNotNull($url);
		}
		lt('simpleFrontRoute', 'lt-SimpleRouterTest');
	}




	/**
	 * @param Nette\Application\IRouter $route
	 * @param $url
	 *
	 * @return Nette\Application\Request|NULL
	 */
	protected function getRequest(Nette\Application\IRouter $route, $url)
	{
		$url = new Nette\Http\UrlScript($url);
		$httpRequest = new Nette\Http\Request($url);
		$request = $route->match($httpRequest);

		return $request;
	}

	protected function getUrl(Nette\Application\IRouter $route, $presenter, $params = array(), $referenceUrl = 'http://example.com')
	{
		$url = new Nette\Http\Url($referenceUrl);
		$request = new Nette\Application\Request($presenter, 'GET', $params);
		$url = $route->constructUrl($request, $url);

		return $url;
	}
}
