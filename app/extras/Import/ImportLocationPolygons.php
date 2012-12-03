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

class ImportLocationsPolygons extends BaseImport {

	public function doImport($subsection = NULL) {

		// // RESET clickMapData in all Location
		// if (false) {
		// 	// trva to strasne dlho!!!!
		// 	foreach (\Service\Location\LocationList::getAll() as $l) {
		// 		$location = \Service\Location\Location::get($l->id);
		// 		$location->clickMapData = array();
		// 		$location->save();
		// 	}
		// }

		// GET data from API
		if ($this->developmentMode == TRUE) {
			$data = json_decode(file_get_contents("http://www.tralandia.sk/trax_maps/_api.php?country=sk"), TRUE);
		} else {
			$data = json_decode(file_get_contents("http://www.tralandia.sk/trax_maps/_api.php"), TRUE);
		}
		
		$countries = array('success'=>array());
		$areas = array('success'=>array());

		foreach ($data['countries'] as $iso => $country) {

			// Get data about country
			$countryName = null;
			$q = q("SELECT c.id, ct.name FROM countries c LEFT JOIN countries_translations ct ON ct.location_id=c.id WHERE c.iso = '".$iso."' AND ct.language_id = 38 LIMIT 1");
			while($c = mysql_fetch_assoc($q)) $countryName = $c['name'];

			$countryId = null;
			$q = qNew("SELECT * FROM location WHERE slug LIKE '".\Nette\Utils\Strings::webalize($countryName)."' LIMIT 1");
			while($c = mysql_fetch_assoc($q)) $countryId = $c['id'];

			if ($countryId) {
				$countries['success'][] = array(
					'id' => $countryId,
					'iso' => $iso,
					'css' => $this->stringifyCss($country['css']),
					'name' => $countryName,
					'otherCountries' => array(56,57,58)
				);
			} else {
				$countries['notFound'][] = array(
					'iso' => $iso,
					'css' => $this->stringifyCss($country['css'])
				);
			}

			// Get data about areas
			foreach ($country['areas'] as $area) {

				$css = array();
				foreach ($country['css'] as $class => $styles) {
					if ((bool)preg_match("/rid{$area['rid']}([^0-9]+|$)/", $class)) {
						$css[$class] = $styles;
						unset($country['css'][$class]);
					}
				}

				if (!$area['name']) {

					$areas['nullIdentifier'][] = array(
						'coords' => $area['coords'],
						'rid' => $area['rid'],
						'country' => $countryName,
						'css' => $this->stringifyCss($css)
					);

				} else {

					$q = qNew("SELECT id, slug FROM location WHERE slug LIKE '".\Nette\Utils\Strings::webalize($area['name'])."'");
					$i = 0;

					$newCss = array();
					while($location = mysql_fetch_assoc($q)) {
						foreach ($css as $class => $styles) {
							$newClass = str_replace("rid{$area['rid']}", "rid{$location['id']}", $class);
							$newCss[$newClass] = $styles;
						}
						$areas['success'][] = array(
							'id' => $location['id'],
							'coords' => $area['coords'],
							'css' => $this->stringifyCss($newCss)
						);
						$i++;
					}

					if (!$i) {

						$areas['notFound'][] = array(
							'name' => $area['name'],
							'coords' => $area['coords'],
							'rid' => $area['rid'],
							'country' => $countryName,
							'css' => $this->stringifyCss($newCss)
						);

					}

				}

			}

		}

		// Merge all success data
		$success = array(
			'areas' => $areas['success'],
			'countries' => $countries['success']
		);

		// Update all success data
		foreach ($success as $key => $data) {
			foreach ($data as $location) {
				$region = $this->context->locationRepositoryAccessor->get()->find($location['id']);
				$region->clickMapData = $location;
				$this->model->persist($region);
			}
		}
		$this->model->flush();
	}

	public function stringifyCss($css) {
		
		$out = null;
		foreach ($css as $class=>$params) {
			$out .= "{$class} { ";
				foreach ($params as $param=>$value) {
					$out .= "{$param}:{$value}; ";
				}
			$out .= "} ";
		}

		return $out;

	}

}