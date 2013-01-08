<?php
namespace Tests\Router;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class CreateNewTest extends BaseRouterTest
{
	public $frontRouteFactory;

	protected function setUp() {
		$this->frontRouteFactory = $this->getContext()->frontRouteFactory;
	}

	public function testCreate() {


	}

}