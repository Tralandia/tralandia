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

	protected function setUp() {
		$this->invoicingDataRepository = $this->getContext()->invoiceInvoicingDataRepositoryAccessor->get();
		$this->invoicingDataRepository = $this->getContext()->languageRepositoryAccessor->get();
		$this->invoiceCreatorFactory = $this->getContext()->invoiceCreatorFactory;
		$this->rental = $this->getContext()->rentalRepositoryAccessor->get()->find(1);
		$this->package = $this->getContext()->invoicePackageRepositoryAccessor->get()->find(1);
		$this->package2 = $this->getContext()->invoicePackageRepositoryAccessor->get()->find(2);
	}

	public function testCreate() {
		$invoicingData = $this->invoicingDataRepository->createNew();
		$invoiceCreator = $this->invoiceCreatorFactory->create($this->rental, $invoicingData, $this->package);
		$invoiceCreator->addPackage($this->package2);
		$invoice = $invoiceCreator->createInvoice();
		$i = 1;
	}

}