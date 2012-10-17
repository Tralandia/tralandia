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

class ImportInvoicing extends BaseImport {

	public function doImport($subsection = NULL) {
		$import = new \Extras\Import\BaseImport();
		$import->undoSection('invoicing');

		$en = \Service\Dictionary\Language::getByIso('en');

		// Invoices
		//$invoiceNameType = $this->createPhraseType('\Invoicing\ServiceDuration', 'name', 'supportedLanguages', 'ACTIVE');

		$this->companiesByOldId = getNewIdsByOld('\Company\Company');
		$this->rentalsByOldId = getNewIdsByOld('\Rental\Rental');
		$this->serviceTypesByOldId = getNewIdsByOld('\Invoicing\ServiceType');

		$this->countryTypeId = qNew('select id from location_type where slug = "country"');
		$this->countryTypeId = mysql_fetch_array($this->countryTypeId);
		$this->locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$this->countryTypeId[0]);
		$this->languagesByOldId = getNewIdsByOld('\Language');

		// Import paid invoices
		if ($this->developmentMode == TRUE) {
			$r = q('select * from invoicing_invoices_paid where client_country_id = 46 order by id');
		} else {
			$r = q('select * from invoicing_invoices_paid order by id');
		}
		
		while($x = mysql_fetch_array($r)) {
			$this->importOneInvoice($x);
		}

		// Import pending invoices
		if ($this->developmentMode == TRUE) {
			$r = q('select * from invoicing_invoices_pending where client_country_id = 46 order by id');
		} else {
			$r = q('select * from invoicing_invoices_pending order by id');
		}
		
		while($x = mysql_fetch_array($r)) {
			$this->importOneInvoice($x);
		}
		
		$this->savedVariables['importedSections']['invoicing'] = 1;
	}

	private function importOneInvoice($x) {
		$invoice = \Service\Invoicing\Invoice::get();
		$invoice->oldId = $x['id'];
		if (isset($x['invoice_number'])) {
			$invoice->invoiceNumber = $x['invoice_number'];
		}
		if (isset($x['invoice_variable_number'])) {
			$invoice->paymentReferenceNumber = $x['invoice_variable_number'];
		}
		$invoice->company = \Service\Company\Company::get($this->companiesByOldId[$x['companies_id']]);
		if (isset($this->rentalsByOldId[$x['objects_id']])) {
			$t = \Service\Rental\Rental::get($this->rentalsByOldId[$x['objects_id']]);
			if ($t) {
				$invoice->rental = \Service\Rental\Rental::get($this->rentalsByOldId[$x['objects_id']]);
			} else {
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
				$invoice->status = \Entity\Invoicing\Invoice::STATUS_PAID;
			} else {
				$invoice->status = \Entity\Invoicing\Invoice::STATUS_PAID_NOT_CHECKED;
			}
		} else {
			$invoice->status = \Entity\Invoicing\Invoice::STATUS_PENDING;
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

		$invoice->clientLanguage = \Service\Dictionary\Language::get($this->languagesByOldId[$x['client_language_id']]);
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
				$currency = \Service\Currency::getByOldId($x1['currencies_id']);			
			}

			$invoiceItem = \Service\Invoicing\Item::get();
			$invoiceItem->oldId = $x['id'];

			$invoiceItem->serviceType = \Service\Invoicing\ServiceType::get($this->serviceTypesByOldId[$x1['services_types_id']]);
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
			$invoiceItem->save();

			$invoice->addItem($invoiceItem);
		}

		// This is done after the items have been imported, as currency is stored for items in old system
		$invoice->currency = $currency;
		if ($currency->exchangeRate === NULL) debug($currency->exchangeRate);
		$invoice->exchangeRate = $currency->exchangeRate;


		$invoice->save();

	}
}