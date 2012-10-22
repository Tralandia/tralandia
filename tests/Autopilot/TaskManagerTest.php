<?php

namespace Autopilot;

use PHPUnit_Framework_TestCase, Nette, Extras;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class TaskManagerTest extends PHPUnit_Framework_TestCase
{
	public $taskManager;

	protected function setUp() {
		$context = Nette\Environment::getContext();
		$this->taskManager = $context->taskManager;
	}

	public function testDefault() {

		$this->taskManager->run();

		$this->assertTrue(true);
	}


}