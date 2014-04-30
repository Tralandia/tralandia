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

/**
 * @backupGlobals disabled
 */
class InvoiceManagerTest extends TestCase
{


	protected $rental;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $service;

	protected $translator;

	protected $invoiceDao;


	public function setUp()
	{
		parent::setUp();

		$localityName = \Tests\Mocks\PhraseFactory::create();
		$locality = \Mockery::mock('\Entity\Locality');
		$locality->shouldReceive('getName')->andReturn($localityName);

		$address = \Mockery::mock('\Entity\Contact\Address');
		$address->shouldReceive('getLocality')->andReturn($locality);

		$currency = \Mockery::mock('\Entity\Currency');

		$rental = \Mockery::mock('\Entity\Rental\Rental');
		$rental->shouldReceive('getAddress')->once()->andReturn($address);
		$rental->shouldReceive('getContactName')->once()->andReturn('contact mame');
		$rental->shouldReceive('getContactEmail')->once()->andReturn('contact email');
		$rental->shouldReceive('getPhone')->once()->andReturnNull();
		$rental->shouldReceive('getCurrency')->andReturn($currency);


		$durationName = \Tests\Mocks\PhraseFactory::create();
		$duration = \Mockery::mock('\Entity\Invoicing\ServiceDuration');
		$duration->shouldReceive('getStrtotime')->andReturn('+1 month');
		$duration->shouldReceive('getName')->andReturn($durationName);

		$service = \Mockery::mock('\Entity\Invoicing\Service');
		$service->shouldReceive('getDuration')->andReturn($duration);


		$translator = \Mockery::mock('\Tralandia\Localization\Translator');
		$translator->shouldReceive('translate')->andReturn('translation');

		$invoiceDao = $this->getEm()->getRepository(INVOICING_INVOICE);


		$this->rental = $rental;
		$this->service = $service;
		$this->translator = $translator;
		$this->invoiceDao = $invoiceDao;
	}

	public function testInvoiceForFree()
	{

		$company = \Mockery::mock('\Entity\Invoicing\Company');
		$company->shouldReceive('getVat')->andReturn(0);

		$companyDao = \Mockery::mock('\Tralandia\BaseDao');
		$companyDao->shouldReceive('findOneBy')->with(['slug' => Company::SLUG_ZERO])->andReturn($company);

		$invoiceManager = new InvoiceManager($this->invoiceDao, $companyDao);

		$service = $this->service;
		$service->shouldReceive('getPriceCurrent')->andReturn(0);
		$service->shouldReceive('isForFree')->andReturn(TRUE);


		$invoice = $invoiceManager->createInvoice($this->rental, $service, 'test', $this->translator);

		$this->assertInstanceOf(INVOICING_INVOICE, $invoice);
	}

	public function testInvoice()
	{

		$company = \Mockery::mock('\Entity\Invoicing\Company');
		$company->shouldReceive('getVat')->andReturn(1);

		$companyDao = \Mockery::mock('\Tralandia\BaseDao');
		$companyDao->shouldReceive('findOneBy')->with(['slug' => Company::SLUG_TRALANDIA_SRO])->andReturn($company);

		$invoiceManager = new InvoiceManager($this->invoiceDao, $companyDao);

		$service = $this->service;
		$service->shouldReceive('getPriceCurrent')->andReturn(1);
		$service->shouldReceive('isForFree')->andReturn(FALSE);


		$invoice = $invoiceManager->createInvoice($this->rental, $service, 'test', $this->translator);

		$this->assertInstanceOf(INVOICING_INVOICE, $invoice);
	}

}
