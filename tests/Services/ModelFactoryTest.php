<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class ModelFactoryTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $taskFactory;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->taskFactory = $this->context->taskFactory;
	}

	public function testBase() {
		$entity = $this->taskFactory->createEntity();
		$this->assertInstanceOf('\Entity\Task\Task', $entity);

		$service = $this->taskFactory->createService($entity);
		$this->assertInstanceOf('\Service\Task\TaskService', $service);

		$this->assertSame($entity, $service->getEntity());

		$service = $this->taskFactory->createService();
		$this->assertInstanceOf('\Entity\Task\Task', $service->getEntity());
	}

}