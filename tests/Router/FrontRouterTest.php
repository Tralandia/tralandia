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


		$this->routeIn($route, 'http://www.sk.tra.com/kalendar-obsadenosti/1?months=8', 'CalendarIframe', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::RENTAL => $this->findRental(1),
			FrontRoute::PAGE => $this->findPage(4),
			'months' => '8',
		));

		$this->routeIn($route, 'http://www.sk.tra.com/external/calendar/calendar.php?id=1&months=8', 'CalendarIframe', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::RENTAL => $this->findRental(1),
			'months' => '8',
		), 'http://www.sk.tra.com/kalendar-obsadenosti/1?months=8');

		$this->routeIn($route, 'http://us.ch.tra.com/', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(158),
			FrontRoute::LANGUAGE => $this->findLanguage(33),
		), 'http://www.ch.tra.com/');


		$this->routeIn($route, 'http://www.usal.tra.com/', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(269),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
		));




		$this->routeIn($route, 'http://www.com.tra.com/baratsagos-hangulu-gibraltar-vendeghaz-a-r280', 'Rental', array(
			'action' => 'detail',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(159),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
			FrontRoute::RENTAL => $this->findRental(280),
		), 'http://en.hu.tra.com/baratsagos-hangulu-gibraltar-vendeghaz-a-r280');

		$this->routeIn($route, 'http://sk.usal.tra.com/registracia', 'Registration', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(269),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::PAGE => $this->findPage(1),
		));

		// ak objekt uz neexistuje
		$this->routeIn($route, 'http://www.sk.tra.com/f', 'RentalList', array(
			'action' => 'redirectToFavorites',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
		));

		$this->routeIn($route, 'http://www.sk.tra.com/external/calendar/calendar.php?id=test&months=8', 'Home', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			'id' => 'test',
			'months' => '8',
		), 'http://www.sk.tra.com/?id=test&months=8');


		$this->routeIn($route, 'http://www.sk.tra.com/prihlasenie', 'Sign', array(
			'action' => 'in',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(56),
			FrontRoute::LANGUAGE => $this->findLanguage(144),
			FrontRoute::PAGE => $this->findPage(2),
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

		$this->routeIn($route, 'http://www.com.tra.com/support-tralandia', 'SupportUs', array(
			'action' => 'default',
			FrontRoute::PRIMARY_LOCATION => $this->findLocation(1),
			FrontRoute::LANGUAGE => $this->findLanguage(38),
			FrontRoute::PAGE => $this->findPage(16),
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
