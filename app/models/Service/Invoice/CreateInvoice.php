<?php
namespace Serivice\Invoice;

use Nette;
use Entity\Rental\Rental;
use Extras\Types\InvoicingData;

/**
 * CreateInvoice class
 *
 * @author Dávid Ďurika
 */
class CreateInvoice extends Nette\Object {

	protected $invoiceRepositoryAccessor;

	/**
	 * @var Rental
	 */
	protected $retnal;

	/**
	 * Faktoracne udaje
	 * @var InvoicingData
	 */
	protected $invoicingData;

	/**
	 * @var array[Entity\Invoice\Package]
	 */
	protected $packages = array();

	/**
	 * @param  \Nette\DI\Container $dic
	 * @return void
	 */
	public function injectDic(\Nette\DI\Container $dic)
	{
		$this->invoiceRepositoryAccessor = $dic->invoiceRepositoryAccessor;
	}

	/**
	 * Sluzba na vyvaranie faktur
	 * @param Rental          $retnal
	 * @param InvoicingData   $invoicingData
	 * @param Invoice\Package $package
	 */
	public function __construct(Rental $retnal, InvoicingData $invoicingData, Invoice\Package $package)
	{
		$this->rental = $rental;
		$this->invoicingData = $invoicingData;
		$this->addPackage($package);
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
	public function createInvocie()
	{
		$invocie = $this->invoiceRepositoryAccessor->get()->createNew();

		$rental = $this->rental;
		$retnal->addInvoice($invocie);

		$invocie->invoicingData = $this->invoicingData;

		$invoice->due = c(new Nette\DateTime)->modify('+7 day');

		return $invoice;
	}

}