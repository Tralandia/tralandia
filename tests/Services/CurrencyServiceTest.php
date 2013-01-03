<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class CurrencyServiceTest extends \Tests\TestCase
{
	public $context;
	public $model;

	protected function setUp() {
		$this->model = $this->context->model;
	}

	public function testProcess() {
		$service = new Service\Currency($this->model, new Entity\Currency);
		$this->assertSame(99, $service->process(44, 55));
	}
}