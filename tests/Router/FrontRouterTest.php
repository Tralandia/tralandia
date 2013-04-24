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
		), 'http://www.sk.tra.com/?device=mobile', 'http://www.hu.tra.com/');
	}


	public function testCompiler() {
		$route = $this->route;

		// ak objekt uz neexistuje
		$this->routeIn($route, 'http://www.sk.tra.com/external/calendar/calendar.php?id=test&months=8', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			'id' => 'test',
			'months' => '8',
		), 'http://www.sk.tra.com/?id=test&months=8');

		$this->routeIn($route, 'http://www.sk.tra.com/external/calendar/calendar.php?id=1&months=8', 'CalendarIframe', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::RENTAL => $this->findRental(1),
			'months' => '8',
		), 'http://www.sk.tra.com/kalendar-obsadenosti/1?months=8');

		$this->routeIn($route, 'http://www.sk.tra.com/prihlasenie', 'Sign', array(
			'action' => 'in',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::PAGE => $this->findPage(3),
		));

		$this->routeIn($route, 'http://www.sk.tra.com/houseboaty', 'RentalList', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::$pathParametersMapper[FrontRoute::RENTAL_TYPE] => $this->findRentalType(14),
		), 'http://www.sk.tra.com/ine');

		$this->routeIn($route, 'http://www.sk.tra.com/liptov/drevenice', 'RentalList', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::$pathParametersMapper[FrontRoute::LOCATION] => $this->findLocation(331),
			FrontRoute::$pathParametersMapper[FrontRoute::RENTAL_TYPE] => $this->findRentalType(10),
		), 'http://www.sk.tra.com/liptov/chaty');

		$this->routeIn($route, 'http://www.com.tra.com/', 'RootHome', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(1),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
		));

		$this->routeIn($route, 'http://www.com.tra.com/contacts', 'Contact', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(1),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
			FrontRoute::PAGE => $this->findPage(2),
		));

		$this->routeIn($route, 'http://sk.usal.tra.com/registracia', 'Registration', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(269),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::PAGE => $this->findPage(1),
		));

		$this->routeIn($route, 'http://www.com.tra.com/registration', 'Registration', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(1),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
			FrontRoute::PAGE => $this->findPage(1),
		));

		$this->routeIn($route, 'http://www.sk.tra.com/registracia', 'Registration', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::PAGE => $this->findPage(1),
		));

	}

}
