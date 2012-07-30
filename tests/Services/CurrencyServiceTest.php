<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class CurrencyServiceTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $model;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->model = $this->context->model;
	}

	public function testCRUD() {
		$service = new Services\Currency($this->model, new Entity\Currency);
		$this->assertInstanceOf('Services\Currency', $service);

		$service->setIso('EUR');
		$this->assertSame('EUR', $service->getIso());

		$service->setExchangeRate(44.66);
		$this->assertSame(44.66, $service->getExchangeRate());

		$service->setRounding(2);
		$this->assertSame(2, $service->getRounding());

		$this->assertTrue($service->save());
		$this->assertInstanceOf('DateTime', $service->getCreated());
		$this->assertInstanceOf('DateTime', $service->getUpdated());
		$this->assertInternalType('integer', $service->getId());

		$entity = $this->model->getRepository('Entity\Currency')->find($service->getId());
		$this->assertInstanceOf('Entity\Currency', $entity);

		$service = new Services\Currency($this->model, $entity);

		$this->assertEquals('EUR', $service->getIso());
		$this->assertEquals(44.66, $service->getExchangeRate());
		$this->assertEquals(2, $service->getRounding());

		$service->setIso('CZK');
		$this->assertEquals('CZK', $service->getIso());

		$service->setRounding(14);
		$this->assertEquals(14, $service->getRounding());

		$this->assertTrue($service->save());
		$this->assertTrue($service->delete());

		$this->setExpectedException('Doctrine\ORM\ORMException');
		$entity = $this->model->getRepository('Entity\Currency')->find($service->getId());
	}

	public function testProcess() {
		$service = new Services\Currency($this->model, new Entity\Currency);
		$this->assertSame(99, $service->process(44, 55));
	}
}