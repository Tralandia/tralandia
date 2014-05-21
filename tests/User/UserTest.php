<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 29/04/14 09:57
 */

namespace Tests\Invoicing;


use Entity\Invoicing\Company;
use Nette;
use Tests\TestCase;
use Tralandia\Invoicing\ClientInformation;
use Tralandia\Invoicing\InvoiceManager;
use Tralandia\Invoicing\ServiceDurationRepository;
use Tralandia\Rental\Rental;

/**
 * @backupGlobals disabled
 */
class UserTest extends TestCase
{


	/**
	 * @var \Tralandia\User\UserRepository
	 */
	protected $userRepository;


	protected function setUp()
	{
		$this->newDataSet(__DIR__ . '/UserTest.sql');
		$this->userRepository = $this->getContext()->getByType('\Tralandia\User\UserRepository');
	}


	public function testInvoicingInformation()
	{
		$user = $this->userRepository->find(1);

		$user->setDefaultInvoicingInformation(['foo' => 'bar', 'clientName' => 'John']);
//		$user->invoicingInformation = ['foo' => 'bar', 'clientName' => 'John'];

		$this->userRepository->save($user);

		$defaultInformation = $user->getDefaultInvoicingInformation();

		$this->assertTrue(is_array($defaultInformation));
		$this->assertArrayNotHasKey('foo', $defaultInformation);
		$this->assertArrayHasKey('clientPhone', $defaultInformation);
		$this->assertEquals(NULL, $defaultInformation['clientPhone']);
		$this->assertArrayHasKey('clientName', $defaultInformation);
		$this->assertEquals('John', $defaultInformation['clientName']);
	}

}
