<?php
namespace Service\Invoice;

use Nette;
use Entity\Rental\Rental;
use Entity\Invoice;
use Repository\Invoice\InvoiceRepository;

/**
 * InvoiceCreator class
 *
 * @author Dávid Ďurika
 */
class InvoiceCreator extends Nette\Object
{

	/**
	 * @var \Repository\Invoice\InvoiceRepository
	 */
	protected $invoiceRepository;

	/**
	 * @var Rental
	 */
	protected $rental;

	/**
	 * Faktoracne udaje
	 * @var Invoice\InvoicingData
	 */
	protected $invoicingData;

	/**
	 * @var array[Entity\Invoice\Package]
	 */
	protected $packages = array();

	/**
	 * Sluzba na vyvaranie faktur
	 *
	 * @param \Entity\Rental\Rental $rental
	 * @param \Entity\Invoice\InvoicingData $invoicingData
	 * @param \Entity\Invoice\Package $package
	 * @param \Repository\Invoice\InvoiceRepository $invoiceRepository
	 */
	public function __construct(Rental $rental, Invoice\InvoicingData $invoicingData, Invoice\Package $package,
								InvoiceRepository $invoiceRepository)
	{
		$this->rental = $rental;
		$this->invoicingData = $invoicingData;
		$this->addPackage($package);
		$this->invoiceRepository = $invoiceRepository;
	}

	/**
	 * Pridavanie balickov do FA
	 * @param Invoice\Package $package
	 */
	public function addPackage(Invoice\Package $package)
	{
		$this->packages[] = $package;
	}

	/**
	 * Vygeneruje FA
	 * @return Invoice\Invoice
	 */
	public function createInvoice()
	{
		/** @var $invoice Invoice\Invoice */
		$invoice = $this->invoiceRepositoryAccessor->get()->createNew();

		$rental = $this->rental;
		$rental->addInvoice($invoice);

		$invoice->invoicingData = $this->invoicingData;

		$invoice->setDue((new Nette\DateTime)->modify('+7 day'));

		return $invoice;
	}

}


interface IInvoiceCreatorFactory {

	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param \Entity\Invoice\InvoicingData $invoicingData
	 * @param \Entity\Invoice\Package $package
	 *
	 * @return InvoiceCreator
	 */
	function create(Rental $rental, Invoice\InvoicingData $invoicingData, Invoice\Package $package);
}