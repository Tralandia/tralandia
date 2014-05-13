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
class InvoiceManagerTest extends TestCase
{

	/**
	 * @var \Tralandia\Invoicing\InvoiceManager
	 */
	protected $invoiceManager;

	/**
	 * @var \Tralandia\Rental\Rental
	 */
	protected $rental;


	protected function setUp()
	{
		$this->invoiceManager = $this->getContext()->getByType('\Tralandia\Invoicing\InvoiceManager');
		$rental = new Rental();

		$this->rental = $rental;
	}


	public function testBasic()
	{
		/** @var $serviceDurationRepository ServiceDurationRepository */
		$serviceDurationRepository =$this->getContext()->getByType('\Tralandia\Invoicing\ServiceDurationRepository');
		$serviceDuration = $serviceDurationRepository->createNew();

		$this->invoiceManager->createInvoice($this->rental, );
	}

}
