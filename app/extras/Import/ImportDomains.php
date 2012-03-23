<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class ImportDomains extends BaseImport {

	public function doImport() {
		$this->savedVariables['importedSections']['domains'] = 1;
		$r = q('select domain from countries where length(domain)>0');
		while($x = mysql_fetch_array($r)) {
			$s = S\DomainService::get();
			$s->domain = $x['domain'];
			$s->save();
		}

		$s = S\DomainService::get();
		$s->domain = 'tralandia.com';
		$s->save();

		$this->savedVariables['importedSections']['domains'] = 2;

	}

}