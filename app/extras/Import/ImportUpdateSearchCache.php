<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportUpdateSearchCache extends BaseImport {

	public function doImport($subsection = NULL) {

		$primaryLocation = $this->context->locationRepositoryAccessor->get()->findOneByIso('sk');
		$this->context->updateRentalSearchKeysCacheRobotFactory->create($primaryLocation)->run();
		
	}

}