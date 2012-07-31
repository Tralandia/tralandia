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
		$service = new Service\Currency($this->model, new Entity\Currency);
		$this->assertInstanceOf('Service\Currency', $service);
	}

	public function testSetterAndGetter() {
		$entity = new Entity\Currency;
		$service = new Service\Currency($this->model, $entity);

		$entity->setIso('EUR');
		$this->assertSame('EUR', $entity->getIso());

		$entity->setExchangeRate(44.66);
		$this->assertSame(44.66, $entity->getExchangeRate());

		$entity->setRounding(2);
		$this->assertSame(2, $entity->getRounding());
	}

	public function testSaveAndFindAndDelete() {
		$entity = new Entity\Currency;
		$service = new Service\Currency($this->model, $entity);
		$entity->setIso('EUR');
		$entity->setExchangeRate(44.66);
		$entity->setRounding(2);

		$this->assertTrue($service->save());
		$this->assertInternalType('integer', $entity->getId());
		$this->assertInstanceOf('DateTime', $entity->getCreated());
		$this->assertInstanceOf('DateTime', $entity->getUpdated());
		
		$entity = $this->model->getRepository('Entity\Currency')->find($entity->getId());
		$this->assertInstanceOf('Entity\Currency', $entity);

		$service = new Service\Currency($this->model, $entity);

		$this->assertSame('EUR', $entity->getIso());
		$this->assertSame(44.66, $entity->getExchangeRate());
		$this->assertSame(2, $entity->getRounding());

		$entity->setIso('CZK');
		$this->assertSame('CZK', $entity->getIso());

		$entity->setRounding(14);
		$this->assertSame(14, $entity->getRounding());

		$this->assertTrue($service->save());
		$this->assertTrue($service->delete());

		$this->setExpectedException('Doctrine\ORM\ORMException');
		$entity = $this->model->getRepository('Entity\Currency')->find($entity->getId());
	}
}