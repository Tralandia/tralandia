<?php
namespace Tests\Router;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class FrontRouterTest extends BaseRouterTest
{
	public $context;
	public $frontRouteFactory;
	public $userServiceFactory;
	public $userRepositoryAccessor;

	protected function setUp() {
		$this->frontRouteFactory = $this->getContext()->frontRouteFactory;
	}

	public function testCompiler() {

		$route = $this->frontRouteFactory->create();

		$this->routeIn($route, 'http://www.sk.tra.com/registracia', 'Registration', array(
			'action' => 'default',
			'country' => 58,
			'language' => 144,
		), 'http://www.sk.tra.com/registracia');

		$this->routeIn($route, 'http://www.sk.tra.com/ticket', NULL);

		$this->routeIn($route, 'http://www.sk.tra.com/nitra', 'Rental', array(
			'action' => 'list',
			'country' => 58,
			'language' => 144,
			'location' => 4020,
	), 'http://www.sk.tra.com/nitra');

//		$this->routeIn($route, 'http://www.sk.tra.com/pozicovna', 'Rental', array(
//			'action' => 'list',
//			'country' => 58,
//			'language' => 144,
//			'attractionType' => 16,
//		), 'http://www.sk.tra.com/pozicovna');


	}

}