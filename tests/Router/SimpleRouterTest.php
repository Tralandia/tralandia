<?php
namespace Tests\Router;

use Nette, Extras;
use Routers\BaseRoute;
use Routers\FrontRoute;


/**
 * @backupGlobals disabled
 */
class SimpleRouterTest extends BaseRouterTest
{
	public $route;

	protected function setUp() {
		//$mask = '//[!<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,4}>.%domain%/]<module (front|owner)>/<presenter>[/<action>[/<id>]]';
		//$mask = '//[!<language ([a-z]{2}|www)>.<host [a-z.]+>/]<module (front|owner|admin)>/<presenter>[/<action>[/<id>]]';
		$mask = '//[![!<www www.>][!<language ([a-z]{2}|www)>.]<host [a-z\\.]+>/]<module (front|owner|admin|map)>/<presenter>[/<action>[/<id>]]';
		$metadata = [
			BaseRoute::PRIMARY_LOCATION => 'sk',
			BaseRoute::LANGUAGE => 'www',
			'presenter' => 'Home',
			'action' => 'list',
		];

		$this->route = $this->getContext()->simpleRouteFactory->create($mask, $metadata);
	}

	public function testCompiler() {
		$route = $this->route;

//		$this->routeOut($route, 'Front:Sign', array(
//			'action' => 'in',
//			'primaryLocation' => $this->findLocation(56),
//			'language' => $this->findLanguage(144),
//		));


		$this->routeOut(
			$route,
			'Owner:Rental',
			array(
				'action' => 'edit',
				'id' => '1',
				FrontRoute::PRIMARY_LOCATION => $this->findLocation(154),
				FrontRoute::LANGUAGE => $this->findLanguage(60),
				BaseRoute::AUTOLOGIN => 'hash',
			),
			'http://www.tralandia.com.hr/owner/rental/edit/1?l=hash',
			'http://www.tralandia.com/admin/rental?dataGrid-grid-filter%5Bsearch%5D=jasminakostovic%40yahoo.com'
		);

		$this->routeIn($route, 'http://www.tralandia.com.hr/owner/rental/edit/1?l=hash', 'Owner:Rental', array(
			'action' => 'edit',
			'id' => '1',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(154),
			FrontRoute::LANGUAGE => $this->findLanguage(60),
			BaseRoute::AUTOLOGIN => 'hash',
		));

		$this->routeIn($route, 'http://www.usal.tralandia.com/admin/foo/bar', 'Admin:Foo', array(
			'action' => 'bar',
			'id' => NULL,
			'primaryLocation' => $this->findLocation(256),
			'language' => $this->findLanguage(38),
		));

		$this->routeIn($route, 'http://www.tralandia.com/owner/rental/first-rental', 'Owner:Rental', array(
			'action' => 'firstRental',
			'id' => NULL,
			'primaryLocation' => $this->findLocation(1),
			'language' => $this->findLanguage(38),
		));

		$this->routeOut($route, 'Front:Registration', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(1),
			'language' => $this->findLanguage(38),
		));

		$this->routeOut($route, 'Front:Registration', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(1),
			'language' => $this->findLanguage(38),
			'page' => $this->findPage(1),
		));


		$this->routeOut($route, 'Front:Home', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(1),
			'language' => $this->findLanguage(38),
		));


		$this->routeOut($route, 'Front:Registration', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(269),
			'language' => $this->findLanguage(144),
			'page' => $this->findPage(1),
		));


		$this->routeIn($route, 'http://www.com.tra.com/front/foo/bar', 'Front:Foo', array(
			'action' => 'bar',
			'id' => NULL,
			'primaryLocation' => $this->findLocation(1),
			'language' => $this->findLanguage(38),
		));

		$this->routeIn($route, 'http://sk.usal.tra.com/owner/foo/bar/baz', 'Owner:Foo', array(
			'action' => 'bar',
			'id' => 'baz',
			'primaryLocation' => $this->findLocation(269),
			'language' => $this->findLanguage(144),
		));

	}
}
