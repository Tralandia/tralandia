<?php
namespace Tests\Router;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class FrontRouterTest extends BaseRouterTest
{
	public $route;

	protected function setUp() {
		$this->route = $this->getContext()->frontRouteFactory->create();
	}

	public function testCompiler() {
		$route = $this->route;

		//$this->routeIn($route, 'http://www.sk.tra.com/front/invoices/forms', NULL);
		//$this->routeIn($route, 'http://www.sk.tra.com/ticket', NULL);

		$this->routeIn($route, 'http://www.sk.tra.com/prihlasenie', 'Sign', array(
			'action' => 'in',
			'primaryLocation' => $this->findLocation(56),
			'language' => $this->findLanguage(144),
			'page' => $this->findPage(3),
		));

		$this->routeIn($route, 'http://www.com.tra.com/', 'RootHome', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(1),
			'language' => $this->findLanguage(38),
		));

		$this->routeIn($route, 'http://www.com.tra.com/contacts', 'Contact', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(1),
			'language' => $this->findLanguage(38),
			'page' => $this->findPage(2),
		));

		$this->routeIn($route, 'http://sk.usal.tra.com/registracia', 'Registration', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(269),
			'language' => $this->findLanguage(144),
			'page' => $this->findPage(1),
		));

		$this->routeIn($route, 'http://www.com.tra.com/registration', 'Registration', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(1),
			'language' => $this->findLanguage(38),
			'page' => $this->findPage(1),
		));

		$this->routeIn($route, 'http://www.sk.tra.com/registracia', 'Registration', array(
			'action' => 'default',
			'primaryLocation' => $this->findLocation(56),
			'language' => $this->findLanguage(144),
			'page' => $this->findPage(1),
		));

//		$this->routeIn($route, 'http://www.sk.tra.com/?flanguage=4', 'Rental', array(
//			'action' => 'list',
//			'flanguage' => 4,
//			'primaryLocation' => 56,
//			'language' => 144,
//		), 'http://www.sk.tra.com/?flanguage=4');
//
//		$this->routeIn($route, 'http://www.sk.tra.com/', 'Home', array(
//			'action' => 'default',
//			'primaryLocation' => 56,
//			'language' => 144,
//		), 'http://www.sk.tra.com/');
//
//
//		$this->routeIn($route, 'http://www.sk.tra.com/nitra/chata?fprice=20', 'Rental', array(
//			'action' => 'list',
//			'primaryLocation' => 56,
//			'language' => 144,
//			'location' => 408,
//			'rentalType' => 6,
//			'fprice' => 20,
//		), 'http://www.sk.tra.com/nitra/chata?fprice=20');
//
//
//		$this->routeIn($route, 'http://www.sk.tra.com/registracia?do=registrationForm-submit', 'Registration', array(
//			'action' => 'default',
//			'primaryLocation' => 56,
//			'language' => 144,
//			'do' => 'registrationForm-submit',
//		), 'http://www.sk.tra.com/registracia?do=registrationForm-submit');
//
//
//		$this->routeIn($route, 'http://www.sk.tra.com/nitra', 'Rental', array(
//			'action' => 'list',
//			'primaryLocation' => 56,
//			'language' => 144,
//			'location' => 408,
//	), 'http://www.sk.tra.com/nitra');

//		$this->routeIn($route, 'http://www.sk.tra.com/pozicovna', 'Front:Rental', array(
//			'action' => 'list',
//			'primaryLocation' => 56,
//			'language' => 144,
//			'attractionType' => 16,
//		), 'http://www.sk.tra.com/pozicovna');


	}

}
