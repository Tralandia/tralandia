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

		$data = json_decode(file_get_contents("http://www.tralandia.sk/trax_maps/_api.php"));
		
		$location = \Service\Location\Location::get();
		foreach ($data->countries as $country) {
			foreach ($country->areas as $area) {
				$q = qNew("SELECT * FROM location_location WHERE slug LIKE '%".\Nette\Utils\Strings::webalize($area->name)."%'");
				while($location = mysql_fetch_assoc($q)) {
					debug($location);
				}
				break;
			}
		}

		// $r = q('select * from languages order by id');
		// while($x = mysql_fetch_array($r)) {
		// $s = D\Language::get();
		// $s->oldId = $x['id'];
		// $s->iso = $x['iso'];
		// $s->supported = (bool)$x['translated'];
		// $s->defaultCollation = $x['default_collation'];
		// $s->details = explode2Levels(';', ':', $x['attributes']);
		// $s->save();

		// }
		// \Extras\Models\Service::flush(FALSE);

		// $this->createPhrasesByOld('\Dictionary\Language', 'name', 'supportedLanguages', 'ACTIVE', 'languages', 'name_dic_id');		
		// $this->savedVariables['importedSections']['languages'] = 1;

	}

}