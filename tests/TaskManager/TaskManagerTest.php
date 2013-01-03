<?php

namespace Autopilot;

use  Nette, Extras;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class TaskManagerTest extends \Tests\TestCase
{
	public $taskManager;

	protected function setUp() {
		$this->context = $context = Nette\Environment::getContext();
		$this->taskManager = $context->taskManager;
	}

	public function testMissingTranslationsScanner() {

		$scanner = $this->context->missingTranslationsScanner;
		$scanner->run();

		$this->assertTrue(true);
	}


}