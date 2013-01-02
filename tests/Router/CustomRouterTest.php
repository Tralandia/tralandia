<?php
namespace Test\Router;

use PHPUnit_Framework_TestCase, Nette, Extras;


/**
 * @backupGlobals disabled
 */
class CustomRouterTest extends BaseRouterTest
{
	public $context;
	public $mainRouteFactory;
	public $userServiceFactory;
	public $userRepositoryAccessor;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->mainRouteFactory = $this->context->mainRouteFactory;
	}

	public function testCompiler() {
		$route = $this->mainRouteFactory->create();

		$request = $this->routeInTest($route, 'http://www.tra.sk/nitra', 'Rental', array(
			'action' => 'list',
			'country' => 58,
			'language' => 144,
			'location' => 609,
		), 'http://www.tra.sk/nitra');

		$request = $this->routeInTest($route, 'http://www.tra.sk/pozicovna', 'Attraction', array(
			'action' => 'list',
			'country' => 58,
			'language' => 144,
			'attractionType' => 16,
		), 'http://www.tra.sk/pozicovna');

	}

}