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
	 * @var \Repository\Invoice\ItemRepository
	 */
	protected $invoiceItemRepository;

	/**
	 * @var Rental
	 */
	protected $rental;

	/**
	 * Faktoracne udaje
	 * @var Invoice\InvoicingData
	 */
	protected $clientInvoicingData;

	/**
	 * @var array[Entity\Invoice\Package]
	 */
	protected $packages = array();

	/**
	 * Sluzba na vyvaranie faktur
	 *
	 * @param \Entity\Rental\Rental $rental
	 * @param \Entity\Invoice\InvoicingData $clientInvoicingData
	 * @param \Entity\Invoice\Package $package
	 * @param \Repository\Invoice\InvoiceRepository $invoiceRepository
	 */
	public function __construct(Rental $rental, Invoice\InvoicingData $clientInvoicingData, Invoice\Package $package,
								InvoiceRepository $invoiceRepository)
	{
		if(!$clientInvoicingData->getLanguage()) {
			throw new \Nette\InvalidArgumentException('Premenna $clientInvoicingData nema nastaveny jazyk');
		}

		$this->rental = $rental;
		$this->clientInvoicingData = $clientInvoicingData;
		$this->addPackage($package);
		$this->invoiceRepository = $invoiceRepository;
		$this->invoiceItemRepository = $invoiceRepository->related('items');
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
	 *
	 * @param \Entity\Currency $currency
	 *
	 * @return Invoice\Invoice
	 */
	public function createInvoice(\Entity\Currency $currency)
	{
		/** @var $invoice Invoice\Invoice */
		$invoice = $this->invoiceRepository->createNew();

		$rental = $this->rental;
		$rental->addInvoice($invoice);

		$invoice->setClientInvoicingData($this->clientInvoicingData);
		$invoice->setCurrency($currency);

		$invoice->setDue((new Nette\DateTime)->modify('+7 day'));

		$this->applyPackages($invoice);

		$invoice->updatePrice();

		return $invoice;
	}

	protected function applyPackages(Invoice\Invoice $invoice)
	{
		# @todo
		foreach($this->packages as $package) {
			/** @var $package \Entity\Invoice\Package */
			foreach($package->getServices() as $service) {
				/** @var $service \Entity\Invoice\Service */

				$clientLanguage = $this->clientInvoicingData->getLanguage();
				$duration = $service->getDuration();

				/** @var $item \Entity\Invoice\Item */
				$item = $this->invoiceItemRepository->createNew();
				$invoice->addItem($item);
				$item->setServiceType($service->getType());
				$item->setName($package->getName()->getTranslationText($clientLanguage));
				$item->setNameEn($package->getName()->getCentralTranslationText());
				$item->setDuration($duration->getDuration());
				$item->setDurationName($duration->getName()->getTranslationText($clientLanguage));
				$item->setDurationNameEn($duration->getName()->getCentralTranslationText());
				$item->setPrice($service->getCurrentPrice());
				$item->setPackageName($package->getName()->getTranslationText($clientLanguage));
				$item->setPackageNameEn($package->getName()->getCentralTranslationText());

			}
		}

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