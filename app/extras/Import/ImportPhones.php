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

class ImportPhones extends BaseImport {

	public function doImport($subsection = NULL) {
		$r = qNew('select oldId, id from location where type_id = 3');
		$countries = array();
		while ($x = mysql_fetch_array($r)) {
			$countries[$x['oldId']] = $x['id'];
		}

		$r = qNew('select value from contact_phone');
		$existingPhones = array();
		while ($x = mysql_fetch_array($r)) {
			$existingPhones[$x['value']] = TRUE;
		}
		
		$r = q('select * from phones_cache where country_id_new > 0');
		while($x = mysql_fetch_array($r)) {
			if (isset($existingPhones[$x['id']])) continue;
			qNew('insert into contact_phone set value = "'.$x['id'].'", international = "'.$x['format_int'].'", national = "'.$x['format_national'].'", primaryLocation_id = '.$countries[$x['country_id_new']], 1);
		}
	}
}