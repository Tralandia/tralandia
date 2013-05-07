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

	public $rentalsByOldId;

	public function doImport($subsection = NULL) {
		$context = $this->context;
		$model = $this->model;

		$this->rentalsByOldId = getNewIdsByOld('\Rental\Rental');

		$r = q('select * from invoicing_invoices_paid where companies_id NOT IN (1,2) order by id');
		
		while($x = mysql_fetch_array($r)) {
			$maxTimeTo = qc('select max(time_to) from invoicing_invoices_services_paid where invoices_id = '.$x['id']);
			if ((int)$maxTimeTo < 1356994800) continue;
			$this->importOneInvoice($x);
		}

		$model->flush();
	}

	private function importOneInvoice($x) {
		$context = $this->context;
		$model = $this->model;

		$r1 = q('select * from invoicing_invoices_services_'.($x['time_paid'] ? 'paid' : 'pending').' where invoices_id = '.$x['id']);

		while ($x1 = mysql_fetch_array($r1)) {

			if ($x1['services_types_id'] != 2) continue;

			$service = $context->rentalServiceRepositoryAccessor->get()->createNew();
			$service->givenFor = 'Paid Invoice';
			$service->serviceType = 'featured';
			$service->oldId = $x['id'];

			if (isset($x1['time_from'])) {
				$service->dateFrom = fromStamp($x1['time_from']);
			}
			if (isset($x1['time_to'])) {
				$service->dateTo = fromStamp($x1['time_to']);
			}

			if (isset($this->rentalsByOldId[$x['objects_id']])) {
				$t = $context->rentalRepositoryAccessor->get()->findOneByOldId($x['objects_id']);
				if (!$t) {
					debug('Nenasiel som rental '.$x['objects_id'].' (stare ID).');
				} else {
					$service->rental = $t;
					$model->persist($service);
				}
			}
		}
	}
}