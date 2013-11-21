<?php
namespace Tests\Router;

use Nette, Extras;
use Routers\FrontRoute;


/**
 * @backupGlobals disabled
 */
class FrontRouterTest extends BaseRouterTest
{

	/**
	 * @var \Routers\FrontRoute
	 */
	public $route;

	protected function setUp() {
		$this->route = $this->getContext()->frontRouteFactory->create();
	}

	public function testMobile() {
		$route = $this->route;

		/** @var $device \Device */
		$device = $this->getContext()->device;
		$device->setDevice(\Device::MOBILE);

		$this->routeOut($route, 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
		), 'http://www.sk.tralandia.com/?device=mobile', 'http://www.hu.tralandia.com/');
	}


	public function testCompiler() {
		$route = $this->route;

		$this->routeIn($route, 'http://et.al.tralandia.com/?capacity=17&board=288', 'RentalList', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(236),
			FrontRoute::LANGUAGE => $this->findLanguage(41),
			FrontRoute::$pathParametersMapper[FrontRoute::CAPACITY] => '17',
			FrontRoute::$pathParametersMapper[FrontRoute::BOARD] => $this->findAmenity(288),
		), 'http://et.tralandia.al/?capacity=17&board=288');

		$this->routeIn($route, 'http://www.tralandia.sk/kalendar-obsadenosti/21853?months=8', 'CalendarIframe', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(52),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::RENTAL => $this->findRental(21853),
			FrontRoute::PAGE => $this->findPage(4),
			'months' => '8',
		));

		$this->routeIn($route, 'http://www.tralandia.com/login', 'Sign', array(
			'action' => 'in',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(1),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
			FrontRoute::PAGE => $this->findPage(2)
		), 'http://www.tralandia.com/login');


		$this->routeIn($route, 'http://ro.al.tralandia.com/', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(236),
			FrontRoute::LANGUAGE => $this->findLanguage(135),
		), 'http://ro.tralandia.al/');

		$this->routeIn($route, 'http://hu.tralandia.com/bosznia-hercegovina/szallodak/2-szemely-szamara', 'RootHome', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(1),
			FrontRoute::LANGUAGE => $this->findLanguage(62),
		), 'http://hu.tralandia.com/');

		$this->routeIn($route, 'http://tr.tralandia.com/christmas-adasi/oteller', 'RootHome', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(1),
			FrontRoute::LANGUAGE => $this->findLanguage(165),
		), 'http://tr.tralandia.com/');

		$this->routeIn($route, 'http://fr.tralandia.com/ghana/appartements/pour-enfants', 'RentalList', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(168),
			FrontRoute::LANGUAGE => $this->findLanguage(48),
			FrontRoute::$pathParametersMapper[FrontRoute::RENTAL_TYPE] => $this->findRentalType(6),
		), 'http://fr.gh.tralandia.com/appartements');

		$this->routeIn($route, 'http://sk.tralandia.com.hr', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(154),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
		), 'http://sk.tralandia.com.hr/');

		$this->routeIn($route, 'http://tralandia.hk', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(156),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
		), 'http://www.tralandia.hk/');

		$this->routeIn($route, 'http://it.tralandia.lu/case-vacanze', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(119),
			FrontRoute::LANGUAGE => $this->findLanguage(73),
		), 'http://it.tralandia.lu/');

		$this->routeIn($route, 'http://www.as.tralandia.com/', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(258),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
		));

		$this->routeIn($route, 'http://www.sk.tralandia.cz/', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(194),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
		), 'http://sk.tralandia.cz/');

		$this->routeIn($route, 'http://www.usal.tralandia.com/registration', 'Registration', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(256),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
			'page' => $this->findPage(1),
		));

		$this->routeIn($route, 'http://hu.usal.tralandia.com/', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(256),
			FrontRoute::LANGUAGE => $this->findLanguage(62),
		));

		$this->routeOut($route, 'Registration', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(256),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
		), 'http://www.usal.tralandia.com/registration', 'http://www.tralandia.com/');

		$this->routeIn($route, 'http://www.usal.tralandia.com/', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(256),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
		));


	}
}
