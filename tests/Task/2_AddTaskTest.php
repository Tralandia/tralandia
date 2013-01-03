<?php

namespace Autopilot;

use  Nette, Extras;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class AddTaskTest extends \Tests\TestCase
{
	public $context;

	protected function setUp() {
	}

	public function testDefault() {

		$autopilot = $this->context->autopilot;

		$taskService = $autopilot->createTask('testTask');
		$taskService->save();

		$this->assertInstanceOf('\Service\Task\TaskService', $taskService);
	}


}