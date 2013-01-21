<?php
namespace Tests\Router;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class FrontRouterListTest extends BaseRouterTest
{
	public $frontRouteListFactory;

	protected function setUp() {
		$this->frontRouteListFactory = $this->getContext()->frontRouteListFactory;
	}

	public function testCompiler() {

		$route = $this->frontRouteListFactory->create();
		$route = new \Nette\Application\Routers\Route('//<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,3}>.%domain%/[<hash .*>]', array(
			'primaryLocation' => 'sk',
			'language' => 'www',
			'presenter' => 'Home',
			'action' => 'default',
		));

		$this->routeIn($route, 'http://www.sk.tra.com/', 'Home', array(
			'action' => 'default',
			'primaryLocation' => 'sk',
			'language' => 'www',
			'hash' => '',
		), 'http://www.sk.tra.com/');

/*		$this->routeIn($route, 'http://www.sk.tra.com/nitra/chaty?fprice=20', 'Front:Rental', array(
			'action' => 'list',
			'primaryLocation' => 56,
			'language' => 144,
			'location' => 4020,
			'fprice' => 20,
		), 'http://www.sk.tra.com/nitra/chaty?fprice=20');
*/
/*		$this->routeIn($route, 'http://www.sk.tra.com/registracia', 'Front:Registration', array(
			'action' => 'default',
			'country' => 58,
			'language' => 144,
		), 'http://www.sk.tra.com/registracia');

		$this->routeIn($route, 'http://www.sk.tra.com/ticket', NULL);

		$this->routeIn($route, 'http://www.sk.tra.com/nitra', 'Front:Rental', array(
			'action' => 'list',
			'country' => 58,
			'language' => 144,
			'location' => 4020,
	), 'http://www.sk.tra.com/nitra');*/

//		$this->routeIn($route, 'http://www.sk.tra.com/pozicovna', 'Front:Rental', array(
//			'action' => 'list',
//			'country' => 58,
//			'language' => 144,
//			'attractionType' => 16,
//		), 'http://www.sk.tra.com/pozicovna');


	}

}