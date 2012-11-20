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

class ImportPathSegments extends BaseImport {

	const TYPE_PAGE = 2;
	const TYPE_ATTRACTION_TYPE = 4;
	const TYPE_LOCATION = 6;
	const TYPE_RENTAL_TYPE = 8;
	const TYPE_TAG = 10;

	public function doImport($subsection = NULL) {

		$this->context->generatePathSegmentsRobot->run();
		return;
	}

}