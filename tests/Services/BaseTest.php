<?php

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class BaseTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $model;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->model = $this->context->model;
	}

	public function testConstruct() {
		$service = new Services\Currency($this->model, new Entity\Currency);
		$this->assertInstanceOf('Services\Currency', $service);
	}

	public function testSetterAndGetter() {
		$service = new Services\Currency($this->model, new Entity\Currency);

		$service->setIso('EUR');
		$this->assertSame('EUR', $service->getIso());

		$service->setExchangeRate(44.66);
		$this->assertSame(44.66, $service->getExchangeRate());

		$service->setRounding(2);
		$this->assertSame(2, $service->getRounding());
	}

	public function testSaveAndFindAndDelete() {
		$service = new Services\Currency($this->model, new Entity\Currency);
		$service->setIso('EUR');
		$service->setExchangeRate(44.66);
		$service->setRounding(2);

		$this->assertTrue($service->save());
		$this->assertInternalType('integer', $service->getId());
		$this->assertInstanceOf('DateTime', $service->getCreated());
		$this->assertInstanceOf('DateTime', $service->getUpdated());
		
		$entity = $this->model->getRepository('Entity\Currency')->find($service->getId());
		$this->assertInstanceOf('Entity\Currency', $entity);

		$service = new Services\Currency($this->model, $entity);

		$this->assertSame('EUR', $service->getIso());
		$this->assertSame(44.66, $service->getExchangeRate());
		$this->assertSame(2, $service->getRounding());

		$service->setIso('CZK');
		$this->assertSame('CZK', $service->getIso());

		$service->setRounding(14);
		$this->assertSame(14, $service->getRounding());

		$this->assertTrue($service->save());
		$this->assertTrue($service->delete());

		$this->setExpectedException('Doctrine\ORM\ORMException');
		$entity = $this->model->getRepository('Entity\Currency')->find($service->getId());
	}
}