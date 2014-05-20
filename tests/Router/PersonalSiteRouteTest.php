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

		$mask = '//[!<www www.>]<rentalSlug [a-z0-9-]{4,}>.%domain%/[!<language [a-z]{2}>]';
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

		$this->routeIn($route, 'http://slniecko.uns-local.sk/', 'PersonalSite:First', array(
			'action' => 'default',
			'rentalSlug' => 'slniecko',
			'rental' => $this->findRental('44941'),
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(52),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
		), 'http://slniecko.uns-local.sk/');

		$this->routeIn($route, 'http://www.slniecko.uns-local.sk/hr', 'PersonalSite:Default', array(
			'action' => 'default',
			'rentalSlug' => 'slniecko',
			'rental' => $this->findRental('44941'),
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(52),
			FrontRoute::LANGUAGE => $this->findLanguage(60),
		), 'http://slniecko.uns-local.sk/hr');

		$this->routeIn($route, 'http://slniecko.uns-local.sk/hr', 'PersonalSite:Default', array(
			'action' => 'default',
			'rentalSlug' => 'slniecko',
			'rental' => $this->findRental('44941'),
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(52),
			FrontRoute::LANGUAGE => $this->findLanguage(60),
		));

		$this->routeOut(
			$route,
			'PersonalSite:Default',
			array(
				'action' => 'default',
				'rental' => $this->findRental('15729'),
				FrontRoute::PRIMARY_LOCATION => $this->findLocation(154),
				FrontRoute::LANGUAGE => $this->findLanguage(60),
			),
			'http://siesta.example.com/hr'
		);


	}
}
