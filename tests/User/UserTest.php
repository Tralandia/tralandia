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


	public function testBasic()
	{

		$user = $this->userRepository->find(1);

		$user->invoicingInformation = [['foo'], 'bar'];
		$this->userRepository->save($user);

		$invoicingInformation = $user->invoicingInformation;

		$this->assertNotNull($invoicingInformation);
	}

}
