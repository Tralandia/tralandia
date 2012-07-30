<?php

require_once 'bootstrap.php';

/**
 * @backupGlobals disabled
 */
class CurrencyServiceTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $model;
	public $presenter;
	public $session;
	public $user;

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->model = $this->context->model;

		/*
		$this->presenter = new FrontModule\HomePresenter($this->context);
		$this->presenter->autoCanonicalize = FALSE;
		$this->user = new Nette\Security\User(new SimpleUserStorage($this->context->session), $this->context);
		$this->user->setAuthenticator(new Nette\Security\SimpleAuthenticator(array('admin' => 'admin')));
		$this->context->removeService('user');
		$this->context->addService('user', $this->user);
		*/
	}

	public function testCRUD() {
		$service = new Services\Currency($this->model, new Entity\Currency);
		$this->assertInstanceOf('Services\Currency', $service);

		$service->setIso('EUR');
		$this->assertEquals('EUR', $service->getIso());

		$service->setExchangeRate(44.66);
		$this->assertEquals(44.66, $service->getExchangeRate());

		$service->setRounding(2);
		$this->assertEquals(2, $service->getRounding());

		$this->assertTrue($service->save());
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
}