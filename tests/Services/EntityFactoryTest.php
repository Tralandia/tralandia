<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class EntityFactoryTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $taskEntityFactory;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->taskEntityFactory = $this->context->taskEntityFactory;
	}

	public function testBase() {
		$entity = $this->taskEntityFactory->create();

		$this->assertInstanceOf('\Entity\Task\Task', $entity);
	}

}