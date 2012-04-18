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
	Service\Log\Change as SLog;

class ImportLocationsPolygons extends BaseImport {

	public function doImport($subsection = NULL) {
		$this->savedVariables['importedSections']['locationsPolygons'] = 1;

		// $r = q('select * from languages order by id');
		// while($x = mysql_fetch_array($r)) {
		// 	$s = D\Language::get();
		// 	$s->oldId = $x['id'];
		// 	$s->iso = $x['iso'];
		// 	$s->supported = (bool)$x['translated'];
		// 	$s->defaultCollation = $x['default_collation'];
		// 	$s->details = explode2Levels(';', ':', $x['attributes']);
		// 	$s->save();

		// }
		// \Extras\Models\Service::flush(FALSE);

		// $this->createPhrasesByOld('\Dictionary\Language', 'name', 'supportedLanguages', 'ACTIVE', 'languages', 'name_dic_id');		
		// $this->savedVariables['importedSections']['languages'] = 1;
	}

}