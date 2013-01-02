<?php
namespace Test\Router;

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
		$this->context = Nette\Environment::getContext();
		$this->frontRouteFactory = $this->context->frontRouteFactory;
	}

	public function testCompiler() {
		$route = $this->frontRouteFactory->create();

		$this->routeIn($route, 'http://www.sk.tra.com/nitra', 'Rental', array(
			'action' => 'list',
			'country' => 58,
			'language' => 144,
			'location' => 609,
		), 'http://www.sk.tra.com/nitra');

		$this->routeIn($route, 'http://www.sk.tra.com/pozicovna', 'Attraction', array(
			'action' => 'list',
			'country' => 58,
			'language' => 144,
			'attractionType' => 16,
		), 'http://www.sk.tra.com/pozicovna');

	}

}