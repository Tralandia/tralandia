<?php


class InvoicingServiceMigration extends \Migration\Migration
{


	public function up()
	{
		$lean = $this->getLean();

		$query = 'select * from invoicing_company where slug = %s';
		$zeroCompany = $this->getLean()->query($query, 'zero')->fetchSingle();

		$serviceTypes = $this->getEm()->getRepository(INVOICING_SERVICE_TYPE)->findAll();
		$serviceTypes = Tools::entitiesMap($serviceTypes, 'slug');

		$today = strtotime('today');
		$query = 'select * from rental_service where dateTo >= %d';
		$result = $lean->query($query, $today);

		$lean->begin();

		$i = 1;
		foreach($result as $serviceRow) {
			$invoice = [];
			$invoice['givenFor'] = $serviceRow->givenFor;
			$invoice['number'] = $i;
			$invoice['variableNumber'] = $i;
			$invoice['company_id'] = $zeroCompany->id;
			$invoice['rental_id'] = $serviceRow->rental->id;
			$invoice['dateDue'] = $serviceRow->created;
			$invoice['datePaid'] = $serviceRow->created;
			$invoice['clientName'] = null;
			$invoice['clientPhone'] = null;
			$invoice['clientEmail'] = null;
			$invoice['clientUrl'] = null;
			$invoice['clientAddress'] = null;
			$invoice['clientAddress2'] = null;
			$invoice['clientLocality'] = null;
			$invoice['clientPostcode'] = null;
			$invoice['clientPrimaryLocation_id'] = null;
			$invoice['clientLanguage_id'] = null;
			$invoice['clientCompanyName'] = null;
			$invoice['clientCompanyId'] = null;
			$invoice['clientCompanyVatId'] = null;
			$invoice['createdBy'] = \Entity\Invoicing\Invoice::CREATED_BY_IMPORT;
			$invoice['vat'] = null;
			$invoice['notes'] = null;
			$invoice['paymentInfo'] = null;
			$invoice['dateFrom'] = $serviceRow->dateFrom;
			$invoice['dateTo'] = $serviceRow->dateTo;
			$invoice['durationStrtotime'] = null;
			$invoice['durationName'] = null;
			$invoice['durationNameEn'] = null;
			$invoice['price'] = null;
			$invoice['currency_id'] = null;
			$invoice['priceEur'] = null;
			$invoice['serviceType_id'] = $serviceTypes[$serviceRow->serviceType]->getId();
			$invoice['serviceName'] = null;
			$invoice['serviceNameEn'] = null;
			$invoice['created'] = $serviceRow->created;
			$invoice['updated'] = $serviceRow->updated;

			$lean->query('insert into [invoicing_invoice]', $invoice);
			$i++;
		}

		$lean->commit();
	}


	public function down()
	{
		$this->executeSqlFromFile('down');
	}


}
