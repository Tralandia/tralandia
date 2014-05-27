<?php
namespace Tests\Router;

use Nette, Extras;
use Routers\BaseRoute;
use Routers\FrontRoute;


/**
 * @backupGlobals disabled
 */
class CustomPersonalSiteRouteTest extends BaseRouterTest
{
	public $route;

	protected function setUp() {
		//$mask = '//[!<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,4}>.%domain%/]<module (front|owner)>/<presenter>[/<action>[/<id>]]';
		//$mask = '//[!<language ([a-z]{2}|www)>.<host [a-z.]+>/]<module (front|owner|admin)>/<presenter>[/<action>[/<id>]]';
//		$mask = '//[!<www www.>]%domain%/[!<language [a-z]{2}>]';
//		$mask = '//<host [a-z\\.]+>/[!<language [a-z]{2}>]';
		$mask = '//<host (?:(?!tralandia|tra-local)[a-z\\.\\-])+>/[!<language [a-z]{2}>]';
		$metadata = [
			'module' => 'PersonalSite',
			'presenter' => 'Default',
			'action' => 'default'
		];

		$this->route = $this->getContext()->customPersonalSiteRouteFactory->create($mask, $metadata);
	}

	public function testCompiler() {
		$route = $this->route;

//		$this->routeOut($route, 'Front:Sign', array(
//			'action' => 'in',
//			'primaryLocation' => $this->findLocation(56),
//			'language' => $this->findLanguage(144),
//		));

		$this->routeIn($route, 'http://www.ubytovanie-hudak.local/', 'PersonalSite:Default', array(
			'action' => 'default',
			'rental' => $this->findRental(21812),
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(52),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
		), 'http://www.ubytovanie-hudak.local/');

		$this->routeIn($route, 'http://www.tralandia.sk/', NULL);

		$this->routeIn($route, 'http://ubytovaniehudak.local/', 'PersonalSite:Default', array(
			'action' => 'default',
			'rental' => $this->findRental(21812),
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(52),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
		), 'http://www.ubytovaniehudak.local/');

		$this->routeIn($route, 'http://www.ubytovaniehudak.local/', 'PersonalSite:Default', array(
			'action' => 'default',
			'rental' => $this->findRental(23551),
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(52),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
		), 'http://www.ubytovaniehudak.local/');

		$this->routeOut(
			$route,
			'PersonalSite:Default',
			array(
				'action' => 'default',
				'rental' => $this->findRental(23551),
				FrontRoute::PRIMARY_LOCATION => $this->findLocation(52),
				FrontRoute::LANGUAGE => $this->findLanguage(60),
			),
			'http://www.ubytovaniehudak.local/hr',
			'http://www.ubytovaniehudak.local/'
		);

	}
}
