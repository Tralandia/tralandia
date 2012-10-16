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

class ImportDomains extends BaseImport {

	public function doImport($subsection = NULL) {
		$r = q('select domain from countries where length(domain)>0');
		$expire = new \Nette\DateTime;
		while($x = mysql_fetch_array($r)) {
			$e = $this->context->domainEntityFactory->create();
			$e->domain = $x['domain'];
			$e->expires = $expire;
			$this->model->persist($e);
		}

		$e = $this->context->domainEntityFactory->create();
		$e->domain = 'tralandia.com';
		$e->expires = $expire;
		$this->model->persist($e);

		$this->model->flush();
	}

}