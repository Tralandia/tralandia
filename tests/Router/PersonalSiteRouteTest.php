<?php
namespace Tests\Router;

use Nette, Extras;
use Nette\Application\Routers\RouteList;
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

		$personalSite = new RouteList('PersonalSite');
		$mask = '//[!<www www.>]<rentalSlug [a-z0-9-]{4,}>.[!<cs [a-z]{2,3}.>]%domain%/[!<language [a-z]{2}>]';
		$metadata = [
			'presenter' => 'Default',
			'action' => 'default'
		];

		$personalSite[] = $this->getContext()->personalSiteRouteFactory->create($mask, $metadata);
		$this->route = $personalSite;
	}

	public function testCompiler() {
		$route = $this->route;

//		$this->routeOut($route, 'Front:CalendarIframe', array(
//			'action' => 'default',
//			'rentla' => $this->findRental('44941'),
//			'months' => '8',
//			'version' => 'old',
//			'primaryLocation' => $this->findLocation(56),
//			'language' => $this->findLanguage(144),
//		), NULL);

		$this->routeIn($route, 'http://brooklands-island-view-apartments.ai.tra-local.com/', 'PersonalSite:Second', array(
			'action' => 'default',
			'rentalSlug' => 'brooklands-island-view-apartments',
			'rental' => $this->findRental(146),
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(237),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
		), 'http://brooklands-island-view-apartments.tra-local.com/');

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
