<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUpdateRentalLocations extends BaseImport {

	public function doImport($subsection = NULL) {

		$country = $this->context->locationRepositoryAccessor->get()->findOneByIso($this->presenter->getParameter('countryIso'));
		$regions = $this->context->locationRepositoryAccessor->get()->findRegionsHavingPolygons($country);
		// d(count($regions)); exit;
		foreach ($regions as $key => $region) {
			$this->context->polygonService->setRentalsForLocation($region);
		}
	$this->model->flush();
	}

}