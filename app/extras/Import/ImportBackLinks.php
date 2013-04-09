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

class ImportBackLinks extends BaseImport {

	public function doImport($subsection = NULL) {
		$r = q('select * from seo_backlinks where country_id > 0 and language_id > 0 and object_id > 0');
		while($x = mysql_fetch_array($r)) {
			$rentalId = mysql_fetch_array(qNew('select id from rental where oldId = '.$x['object_id']))[0];
			if (!$rentalId) continue;

			qNew('insert into seo_backlink set 
				rental_id = "'.$rentalId.'", 
				url = "'.$x['url'].'"
				'
			);
		}
	}
}