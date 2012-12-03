<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

class ImportInvoice extends BaseImport {

	public $companiesByOldId;
	public $rentalsByOldId;
	public $serviceTypesByOldId;
	public $countryTypeId;
	public $locationsByOldId;
	public $languagesByOldId;

	public function doImport($subsection = NULL) {
		$context = $this->context;
		$model = $this->model;

		$en = $context->languageRepositoryAccessor->get()->findOneByIso('en');

		// Invoices
		//$invoiceNameType = $this->createPhraseType('\Invoice\ServiceDuration', 'name', 'supportedLanguages', 'ACTIVE');

		$this->companiesByOldId = getNewIdsByOld('\Company\Company');
		$this->rentalsByOldId = getNewIdsByOld('\Rental\Rental');
		$this->serviceTypesByOldId = getNewIdsByOld('\Invoice\ServiceType');

		$this->countryTypeId = qNew('select id from location_type where slug = "country"');
		$this->countryTypeId = mysql_fetch_array($this->countryTypeId);
		$this->locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$this->countryTypeId[0]);
		$this->languagesByOldId = getNewIdsByOld('\Language');

		// Import paid invoices
		if ($this->developmentMode == TRUE) {
			$r = q('select * from invoicing_invoices_paid where companies_id NOT IN (1,2) AND client_country_id = 46 order by id');
		} else {
			$r = q('select * from invoicing_invoices_paid where companies_id NOT IN (1,2) order by id');
		}
		
		while($x = mysql_fetch_array($r)) {
			$this->importOneInvoice($x);
		}

		// Import pending invoices
		if ($this->developmentMode == TRUE) {
			$r = q('select * from invoicing_invoices_pending where companies_id NOT IN (1,2) AND client_country_id = 46 order by id');
		} else {
			$r = q('select * from invoicing_invoices_pending where companies_id NOT IN (1,2) order by id');
		}
		
		while($x = mysql_fetch_array($r)) {
			$this->importOneInvoice($x);
		}
		$model->flush();
	}

	private function importOneInvoice($x) {
		$context = $this->context;
		$model = $this->model;

		$invoice = $context->invoiceEntityFactory->create();
		$invoice->oldId = $x['id'];
		if (isset($x['invoice_number'])) {
			$invoice->invoiceNumber = $x['invoice_number'];
		}
		if (isset($x['invoice_variable_number'])) {
			$invoice->paymentReferenceNumber = $x['invoice_variable_number'];
		}
		$invoice->company = $context->companyRepositoryAccessor->get()->find($this->companiesByOldId[$x['companies_id']]);
		if (isset($this->rentalsByOldId[$x['objects_id']])) {
			$t = $context->rentalRepositoryAccessor->get()->find($this->rentalsByOldId[$x['objects_id']]);
			if (!$t) {
				debug('Nenasiel som rental '.$x['objects_id'].' (stare ID).');
			}
		}
		if (isset($x['time_due'])) {
			$invoice->due = fromStamp($x['time_due']);
		}
		if (isset($x['time_due'])) {
			$invoice->paid = fromStamp($x['time_paid']);
		}

		if ($x['time_paid'] > 0) {
			if ($x['ok']) {
				$invoice->status = $invoice::STATUS_PAID;
			} else {
				$invoice->status = $invoice::STATUS_PAID_NOT_CHECKED;
			}
		} else {
			$invoice->status = $invoice::STATUS_PENDING;
		}

		$invoice->clientName = $x['client_name'];
		$invoice->clientPhone = $x['client_phone'];
		$invoice->clientEmail = $x['client_email'];
		$invoice->clientUrl = new \Extras\Types\Url($x['client_url']);
		$invoice->clientAddress = new \Extras\Types\Address(array(
			'address' => array_filter(array($x['client_address'], $x['client_address_2'])),
			'postcode' => $x['client_postcode'],
			'locality' => $x['client_locality'],
			'country' => $this->locationsByOldId[$x['client_country_id']],
		));

		$invoice->clientLanguage = $context->languageRepositoryAccessor->get()->find($this->languagesByOldId[$x['client_language_id']]);
		$invoice->clientCompanyName = $x['client_company_name'];
		$invoice->clientCompanyId = $x['client_company_id'];
		$invoice->clientCompanyVatId = $x['client_company_vat_id'];
		
		$invoice->vat = $x['vat'];
		$invoice->createdBy = $x['created_by'];
		$invoice->referrer = $x['referrer'];
		$invoice->referrerCommission = $x['telmark_operator_commission'];
		$invoice->paymentInfo = @unserialize($x['payment_info']);

		$r1 = q('select * from invoicing_invoices_services_'.($x['time_paid'] ? 'paid' : 'pending').' where invoices_id = '.$x['id']);
		$currency = FALSE;

		while ($x1 = mysql_fetch_array($r1)) {
			if (!$currency) {
				$currency = $context->currencyRepositoryAccessor->get()->findOneByOldId($x1['currencies_id']);			
			}

			$invoiceItem = $context->invoiceItemEntityFactory->create();
			$invoiceItem->oldId = $x['id'];

			$invoiceItem->serviceType = $context->invoiceServiceTypeRepositoryAccessor->get()->find($this->serviceTypesByOldId[$x1['services_types_id']]);
			$invoiceItem->name = $x1['service_name'];
			$invoiceItem->nameEn = $x1['service_name_en'];
			if (isset($x1['time_from'])) {
				$invoiceItem->serviceFrom = fromStamp($x1['time_from']);
			}
			if (isset($x1['time_to'])) {
				$invoiceItem->serviceTo = fromStamp($x1['time_to']);
			}
			$invoiceItem->durationName = $x1['duration_name'];
			$invoiceItem->durationNameEn = $x1['duration_name_en'];
			$invoiceItem->price = $x1['price'];
			$invoiceItem->marketingName = $x1['marketings_name'];
			$invoiceItem->marketingNameEn = $x1['marketings_name_en'];
			$invoiceItem->couponName = $x1['coupons_name'];
			//$invoiceItem->couponNameEn = $x1['coupons_name_en'];
			$invoiceItem->packageName = $x1['packages_name'];
			$invoiceItem->packageNameEn = $x1['packages_name_en'];

			$invoice->addItem($invoiceItem);
		}

		// This is done after the items have been imported, as currency is stored for items in old system
		$invoice->currency = $currency;
		if ($currency->exchangeRate === NULL) debug($currency->exchangeRate);
		$invoice->exchangeRate = $currency->exchangeRate;


		$model->persist($invoice);
	}

}