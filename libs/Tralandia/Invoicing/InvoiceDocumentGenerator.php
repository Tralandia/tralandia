<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 22/05/14 12:32
 */

namespace Tralandia\Invoicing;


use Environment\Environment;
use Nette;
use OndrejBrejla\Eciovni\Eciovni;
use OndrejBrejla\Eciovni\ParticipantBuilder;
use OndrejBrejla\Eciovni\ItemImpl;
use OndrejBrejla\Eciovni\DataBuilder;
use OndrejBrejla\Eciovni\TaxImpl;
use Tralandia\Invoicing\Invoice;


class InvoiceDocumentGenerator
{
	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	function __construct(Environment $environment)
	{
		$this->environment = $environment;
	}


	/**
	 * @param Invoice $invoice
	 *
	 * @return Eciovni
	 */
	public function getEciovni(Invoice $invoice)
	{
		$dateNow = $invoice->created;
		$dateExp = $invoice->dateDue;


		$supplier = $this->getSupplier($invoice);
		$customer = $this->getCustomer($invoice);
		$items = $this->getItems($invoice);

		$dataBuilder = new DataBuilder($invoice->number, 'Invoice', $supplier, $customer, $dateExp, $dateNow, $items);
		$dataBuilder->setVariableSymbol($invoice->variableNumber)->setDateOfVatRevenueRecognition($dateNow);
		$data = $dataBuilder->build();

		$eciovni = new Eciovni($data);

		$eciovni->getTemplate()->setTranslator($this->environment->getTranslator());

		$eciovni->setTemplatePath(__DIR__ . '/invoiceDocument.latte');

		return $eciovni;
	}


	protected function getSupplier(Invoice $invoice)
	{
		$company = $invoice->company;

		$builder = new ParticipantBuilder($company->name, $company->address, $company->address2, $company->locality, $company->postcode);
		$builder
			->setIn($company->companyId)
			->setTin($company->companyVatId)
			->setAccountNumber('mock / 1111')
			->setVatPayer(TRUE);

		return $builder->build();
	}

	protected function getCustomer(Invoice $invoice)
	{
		$builder = new ParticipantBuilder($invoice->clientName, $invoice->clientAddress, $invoice->clientAddress2, $invoice->clientLocality, $invoice->clientPostcode);
		$builder
			->setIn($invoice->clientCompanyId)
			->setTin($invoice->clientCompanyVatId)
			->setAccountNumber('mock / 1111');

		return $builder->build();
	}

	protected function getItems(Invoice $invoice)
	{
		$items = [];
		if($invoice->isPaid()) {
			$pricePeriods = $invoice->getPricePeriods();
			foreach($pricePeriods as $period) {
				$items[] = new ItemImpl($invoice->serviceName, 1, 1, $period->price, TaxImpl::fromPercent(22));
			}
		} else {
			$items[] = new ItemImpl($invoice->serviceName, 1, 1, $invoice->price, TaxImpl::fromPercent(22));
		}

		return $items;
	}




}
