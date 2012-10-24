<?php

namespace Autopilot;

use PHPUnit_Framework_TestCase, Nette, Extras;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class AddTaskTest extends PHPUnit_Framework_TestCase
{
	public $context;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
	}

	public function testDefault() {

		$autopilot = $this->context->autopilot;

		$taskService = $autopilot->createTask('testTask');
		$taskService->save();

		$this->assertInstanceOf('\Service\Task\TaskService', $taskService);
	}


}