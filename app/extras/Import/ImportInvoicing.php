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
	Service\Log\Change as SLog;

class ImportInvoicing extends BaseImport {

	public function doImport($subsection = NULL) {

		$en = \Service\Dictionary\Language::getByIso('en');

		// Invoices
		//$invoiceNameType = $this->createDictionaryType('\Invoicing\Service\Duration', 'name', 'supportedLanguages', 'ACTIVE');

		$companiesByOldId = getNewIdsByOld('\Company\Company');
		$rentalsByOldId = getNewIdsByOld('\Rental\Rental');
		$serviceTypesByOldId = getNewIdsByOld('\Invoicing\Service\Type');

		$countryTypeId = qNew('select id from location_type where slug = "country"');
		$locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$countryTypeId);
		$languagesByOldId = getNewIdsByOld('\Dictionary\Language');

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
		$invoice->invoiceNumber = $x['invoice_number'];
		$invoice->paymentReferenceNumber = $x['invoice_variable_number'];
		$invoice->company = $companiesByOldId[$x['companies_id']];
		$invoice->rental = $rentalsByOldId[$x['objects_id']];
		$invoice->due = fromStamp($x['time_due']);
		$invoice->paid = fromStamp($x['time_paid']);
		$invoice->checked = (bool)$x['ok'];

		$invoice->clientName = $x['client_name'];
		$invoice->clientPhone = $x['client_phone'];
		$invoice->clientEmail = $x['client_email'];
		$invoice->clientUrl = new \Extras\Types\Address($x['client_url']);
		$invoice->clientAddress = new \Extras\Types\Address(array(
			'address' => array_filter(array($x['client_address'], $x['client_address_2'])),
			'postcode' => $x['client_postcode'],
			'locality' => $x['client_locality'],
			'country' => locationsByOldId($x['country_id']),
		));

		$invoice->clientLanguage = $languagesByOldId($x['client_language_id']);
		$invoice->clientCompanyName = $x['client_company_name'];
		$invoice->clientCompanyId = $x['client_company_id'];
		$invoice->clientCompanyVatId = $x['client_company_vat_id'];
		
		$invoice->vat = $x['vat'];
		$invoice->exchangeRate = 0; //@todo
		$invoice->createdBy = $x['created_by'];
		$invoice->referrer = $x['referrer'];
		$invoice->referrerCommission = $x['telmark_operator_commission'];
		$invoice->paymentInfo = unserialize($x['payment_info']);

		$r1 = q('select * from invoicing_invoices_services_'.($x['time_paid'] ? 'paid' : 'pending').' where invoices_id = '.$x['id']);
		while ($x1 = mysql_fetch_array($r1)) {
			$invoiceItem = \Service\Invoicing\Item::get();
			$invoiceItem->oldId = $x['id'];

			$invoiceItem->serviceType = $serviceTypesByOldId[$x['services_types_id']];
			$invoiceItem->name = $x['service_name'];
			$invoiceItem->nameEn = $x['service_name_en'];
			$invoiceItem->serviceFrom = $x['time_from'];
			$invoiceItem->serviceTo = $x['time_to'];
			$invoiceItem->durationName = $x['duration_name'];
			$invoiceItem->durationNameEn = $x['duration_name_en'];
			$invoiceItem->price = $x['price'];
			$invoiceItem->marketingName = $x['marketings_name'];
			$invoiceItem->marketingNameEn = $x['marketings_name_en'];
			$invoiceItem->couponName = $x['coupons_name'];
			$invoiceItem->couponNameEn = $x['coupons_name_en'];
			$invoiceItem->packageName = $x['packages_name'];
			$invoiceItem->packageNameEn = $x['packages_name_en'];
			$invoiceItem->save();

			$invoice->addItem($invoiceItem);
		}

		$invoice->save();

	}
}