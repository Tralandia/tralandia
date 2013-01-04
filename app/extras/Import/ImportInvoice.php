<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Validators,
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

		$this->locationTypes = array();
		$this->locationTypes['country'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('country');
		$this->locationTypes['continent'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('continent');
		$this->locationTypes['region'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('region');
		$this->locationTypes['locality'] = $context->locationTypeRepositoryAccessor->get()->findOneBySlug('locality');


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
			$r = q('select * from invoicing_invoices_pending where companies_id NOT IN (1,2) AND time_created > '.(time()-(3*30*24*60*60)).' AND client_country_id = 46 order by id');
		} else {
			$r = q('select * from invoicing_invoices_pending where companies_id NOT IN (1,2) AND time_created > '.(time()-(3*30*24*60*60)).' order by id');
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
		// if (isset($x['invoice_variable_number'])) {
		// 	$invoice->paymentReferenceNumber = $x['invoice_variable_number'];
		// }
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
		if (isset($x['time_paid'])) {
			$invoice->paid = fromStamp($x['time_paid']);
		}

		$invoicingData = $this->context->invoiceInvoicingDataRepositoryAccessor->get()->createNew();
		$invoicingData->name = $x['client_name'];
		$invoicingData->email = $x['client_email'];
		$invoicingData->phone = $x['client_phone'];
		$invoicingData->url = $x['client_url'];
		$invoicingData->address = implode("\n", array_filter(array(
			$x['client_address'],
			$x['client_address_2'],
			$x['client_postcode'],
			$x['client_locality'],
		)));

		$invoicingData->primaryLocation = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$x['client_country_id'], 'type' => $this->locationTypes['country']));

		$invoicingData->companyName = $x['client_company_name'];
		$invoicingData->companyId = $x['client_company_id'];
		$invoicingData->companyVatId = $x['client_company_vat_id'];
		$this->model->persist($invoicingData);

		$invoice->invoicingData = $invoicingData;
		
		$invoice->vat = (float)$x['vat'];
		$invoice->createdBy = $x['created_by'];
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
			$invoiceItem->price = (float)$x1['price'];
			$invoiceItem->packageName = $x1['packages_name'];
			$invoiceItem->packageNameEn = $x1['packages_name_en'];

			$invoice->addItem($invoiceItem);
		}

		// This is done after the items have been imported, as currency is stored for items in old system
		$invoice->currency = $currency;
		if ($currency->exchangeRate === NULL) debug('Currency has no exchange rate', $currency->exchangeRate);
		$invoice->exchangeRate = $currency->exchangeRate;
		$model->persist($invoice);
	}

}