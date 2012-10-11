<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class ServiceFactoryTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $taskRepository;
	public $taskServiceFactory;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->taskRepository = $this->context->taskRepository;
		$this->taskServiceFactory = $this->context->taskServiceFactory;
	}

	public function testBase() {
		$entity = $this->taskRepository->find(1);
		$service = $this->taskServiceFactory->create($entity);

		$this->assertInstanceOf('\Service\Task\TaskService', $service);
	}

}