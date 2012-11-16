<?php
namespace Test\Router;

use PHPUnit_Framework_TestCase, Nette, Extras;

require_once __DIR__ . '/../bootstrap.php';


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
		$request = $this->testRouteIn($route, 'http://dev.tra.com/', 'Rental', array(
			'action' => 'detail',
			'id' => '3'
		), 'http://dev.tra.com/');
	}

}