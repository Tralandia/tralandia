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
		
		$countries = array('success'=>array());
		$areas = array('success'=>array());

		$location = \Service\Location\Location::get();
		foreach ($data->countries as $iso => $country) {


			// Get data about country
			$countryName = null;
			$q = q("SELECT c.id, ct.name FROM countries c LEFT JOIN countries_translations ct ON ct.location_id=c.id WHERE c.iso = '".$iso."' AND ct.language_id = 38 LIMIT 1");
			while($c = mysql_fetch_assoc($q)) $countryName = $c['name'];

			$countryId = null;
			$q = qNew("SELECT * FROM location_location WHERE slug LIKE '".\Nette\Utils\Strings::webalize($countryName)."' LIMIT 1");
			while($c = mysql_fetch_assoc($q)) $countryId = $c['id'];

			if ($countryId) {
				$countries['success'][] = array(
					'id' => $countryId,
					'iso' => $iso,
					'css' => $country->css,
					'name' => $countryName
				);
			} else {
				$countries['notFound'][] = array(
					'iso' => $iso,
					'css' => $country->css
				);
			}

			// Get data about areas
			foreach ($country->areas as $area) {

				$css = array();
				foreach ($country->css as $class => $styles) {
					if ((bool)preg_match("/rid{$area->rid}([^0-9]+|$)/", $class)) {
						$css[$class] = $styles;
						unset($country->css->{$class});
					}
				}

				if (!$area->name) {

					$areas['nullIdentifier'][] = array(
						'coords' => $area->coords,
						'rid' => $area->rid,
						'country' => $countryName,
						'css' => $css
					);

				} else {

					$q = qNew("SELECT id, slug FROM location_location WHERE slug LIKE '".\Nette\Utils\Strings::webalize($area->name)."'");
					$i = 0;

					while($location = mysql_fetch_assoc($q)) {
						$newCss = array();
						foreach ($css as $class => $styles) {
							$newClass = str_replace("rid{$area->rid}", "rid{$location['id']}", $class);
							$newCss[$newClass] = $styles;
						}
						$areas['success'][] = array(
							'id' => $location['id'],
							'coords' => $area->coords,
							'css' => $newCss
						);
						$i++;
					}

					if (!$i) {

						$areas['notFound'][] = array(
							'name' => $area->name,
							'coords' => $area->coords,
							'rid' => $area->rid,
							'country' => $countryName,
							'css' => $css
						);

					}

				}

			}

		}

		// merge all success data
		$success = array(
			'areas' => $areas['success'],
			'countries' => $countries['success']
		);

		foreach ($success as $key => $data) {
			foreach ($data as $location) {
				$region = \Service\Location\Location::get($location['id']);
				$region->clickMapData = $location;
				$region->save();
			}
		}

	}

}