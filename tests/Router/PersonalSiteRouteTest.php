<?php
namespace Tests\Router;

use Nette, Extras;
use Routers\BaseRoute;
use Routers\FrontRoute;


/**
 * @backupGlobals disabled
 */
class PersonalSiteRouteTest extends BaseRouterTest
{
	public $route;

	protected function setUp() {
		//$mask = '//[!<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,4}>.%domain%/]<module (front|owner)>/<presenter>[/<action>[/<id>]]';
		//$mask = '//[!<language ([a-z]{2}|www)>.<host [a-z.]+>/]<module (front|owner|admin)>/<presenter>[/<action>[/<id>]]';
		$mask = '//<rentalSlug [a-z0-9-]{4,}>.%domain%/[!<language [a-z]{2}>]';
		$metadata = [
			'module' => 'PersonalSite',
			'presenter' => 'Default',
			'action' => 'default'
		];

		$this->route = $this->getContext()->personalSiteRouteFactory->create($mask, $metadata);
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
			'PersonalSite:Default',
			array(
				'action' => 'default',
				'rental' => $this->findRental('15729'),
				FrontRoute::PRIMARY_LOCATION => $this->findLocation(154),
				FrontRoute::LANGUAGE => $this->findLanguage(60),
			),
			'http://mroz.example.com/hr'
		);

		$this->routeIn($route, 'http://mroz.example.com/hr', 'PersonalSite:Default', array(
			'action' => 'default',
			'slug' => 'mroz',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(154),
			FrontRoute::LANGUAGE => $this->findLanguage(60),
		));

	}
}
