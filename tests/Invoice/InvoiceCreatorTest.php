<?php
namespace Tests\Rental;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class InvoiceCreatorTest extends \Tests\TestCase
{


	/**
	 * @var \Service\Invoice\IInvoiceCreatorFactory
	 */
	public $invoiceCreatorFactory;

	/**
	 * @var \Entity\Rental\Rental
	 */
	public $rental;

	/**
	 * @var \Repository\Invoice\InvoicingDataRepository
	 */
	public $invoicingDataRepository;

	/**
	 * @var \Entity\Invoice\Package
	 */
	public $package;

	/**
	 * @var \Entity\Invoice\Package
	 */
	public $package2;

	/**
	 * @var \Entity\Currency
	 */
	public $currency;

	/**
	 * @var \Entity\Language
	 */
	public $language;

	protected function setUp() {
		$this->invoicingDataRepository = $this->getContext()->invoiceInvoicingDataRepositoryAccessor->get();
		$this->currency = $this->getContext()->currencyRepositoryAccessor->get()->find(1);
		$this->language = $this->getContext()->languageRepositoryAccessor->get()->find(144);
		$this->invoiceCreatorFactory = $this->getContext()->invoiceCreatorFactory;
		$this->rental = $this->getContext()->rentalRepositoryAccessor->get()->find(1);
		$this->package = $this->getContext()->invoicePackageRepositoryAccessor->get()->find(1);
		$this->package2 = $this->getContext()->invoicePackageRepositoryAccessor->get()->find(2);
	}

	public function testCreate() {

		/** @var $invoicingData \Entity\Invoice\InvoicingData */
		$invoicingData = $this->invoicingDataRepository->createNew();
		$invoicingData->setLanguage($this->language);
		$invoiceCreator = $this->invoiceCreatorFactory->create($this->rental, $invoicingData, $this->package);
		$invoiceCreator->addPackage($this->package2);
		$invoice = $invoiceCreator->createInvoice($this->currency);

		$this->assertGreaterThan(10, $invoice->getPrice());
	}

}