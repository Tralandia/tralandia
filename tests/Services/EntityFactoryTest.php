<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class EntityFactoryTest extends \Tests\TestCase
{
	public $context;
	public $taskEntityFactory;

	protected function setUp() {
		$this->taskEntityFactory = $this->context->taskEntityFactory;
	}

	public function testBase() {
		$entity = $this->taskEntityFactory->create();

		$this->assertInstanceOf('\Entity\Task\Task', $entity);
	}

}