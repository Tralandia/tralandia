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
		$r = qNew('select oldId, id from location where type_id = 3');
		$countries = array();
		while ($x = mysql_fetch_array($r)) {
			$countries[$x['oldId']] = $x['id'];
		}
		
		$r = qNew('select oldId, id from language');
		$languages = array();
		while ($x = mysql_fetch_array($r)) {
			$languages[$x['oldId']] = $x['id'];
		}

		$r = q('select * from seo_backlinks where country_id > 0 and language_id > 0 and object_id > 0');
		while($x = mysql_fetch_array($r)) {
			$rentalId = mysql_fetch_array(qNew('select id from rental where oldId = '.$x['object_id']))[0];
			if (!$rentalId) continue;

			qNew('insert into seo_backlink set 
				rental_id = "'.$rentalId.'", 
				language_id = '.$languages[$x['language_id']].',
				location_id = '.$countries[$x['country_id']].',
				lastChecked = "'.date("Y-m-d H:i:s", $x['created']).'", 
				status = "'.$x['status'].'",
				url = "'.$x['url'].'",
				pageRank = "'.$x['page_rank'].'",
				htmlCode = "'.mysql_real_escape_string($x['html']).'",
				notes = "'.$x['notes'].'"
				'
			);
		}
	}
}