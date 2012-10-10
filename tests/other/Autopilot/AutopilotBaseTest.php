<?php

namespace Autopilot;

use PHPUnit_Framework_TestCase, Nette, Extras;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class AutopilotBaseTest extends PHPUnit_Framework_TestCase
{
	public $context;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
	}

	public function testDefault() {

		

		$this->assertSame('1', '1');
	}


}