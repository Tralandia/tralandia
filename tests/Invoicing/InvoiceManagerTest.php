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
		$this->newDataSet(__DIR__ . '/InvoiceManagerTest.sql');
		$this->invoiceManager = $this->getContext()->getByType('\Tralandia\Invoicing\InvoiceManager');
	}


	public function testBasic()
	{
		/** @var $rental \Tralandia\Rental\Rental */
		$rental = $this->getContext()->getByType('\Tralandia\Rental\RentalRepository')->find(1);
		$service = $this->getContext()->getByType('\Tralandia\Invoicing\ServiceRepository')->find(1);
		$translator = $this->getContext()->getService('translatorFactory')->create($this->findLanguage(38));

		$invoice = $this->invoiceManager->createInvoice($rental, $service, 'test', $translator);


		$this->assertNotNull($invoice);
		$this->assertEquals('clientName', $invoice->clientName);
	}

}
