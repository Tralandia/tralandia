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

class ImportAttractions extends BaseImport {

	public function doImport($subsection = NULL) {

		$en = \Service\Dictionary\Language::getByIso('en');

		$temp = array(
			'multitranslationRequired' => TRUE,
			'webalizedRequired' => TRUE,
		);
		$typeNameType = $this->createDictionaryType('\Attraction\Type', 'name', 'supportedLanguages', 'ACTIVE', $temp);

		$r = q('select * from attractions_types order by id');
		while($x = mysql_fetch_array($r)) {
			$attractionType = \Service\Attraction\Type::get();
			$attractionType->name = $this->createNewPhrase($typeNameType, $x['name_dic_id']);
			$attractionType->save();
		}

		\Extras\Models\Service::flush(FALSE);

		$attractionNameType = $this->createDictionaryType('\Attraction\Attraction', 'name', 'incomingLanguages', 'ACTIVE');
		$attractionDescriptionType = $this->createDictionaryType('\Attraction\Attraction', 'descrition', 'incomingLanguages', 'ACTIVE');

		$countryTypeId = qNew('select id from location_type where slug = "country"');
		$locationsByOldId = getNewIdsByOld('\Location\Location', 'type_id = '.$countryTypeId);
		$languagesByOldId = getNewIdsByOld('\Dictionary\Language');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from attractions limit 20');
		} else {
			$r = q('select * from attractions order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$attraction = \Service\Attraction\Attraction::get();
			$attraction->type = \Service\Attraction\Type::getByOldId($x['attraction_type_id']);
			$attraction->name = $this->createNewPhrase($attractionNameType, $x['name_dic_id']);
			$attraction->description = $this->createNewPhrase($attractionDescriptionType, $x['description_dic_id']);
			$attraction->country = $locationsByOldId[$x['country_id']];
			$attraction->latitude = new \Extras\Types\Latlong($x['latitude']);
			$attraction->longitude = new \Extras\Types\Latlong($x['longitude']);

			if(\Nette\Utils\Validators::isEmail($x['email'])) {
				$attraction->addContact($this->createContact('email', $x['email']));
			}

			if (strlen($x['phone'])) {
				$attraction->addContact($this->createContact('phone', $x['phone']));
			}

			if(\Nette\Utils\Validators::isUrl($x['url'])) {
				$attraction->addContact($this->createContact('url', $x['url']));
			}

			// Media
			$temp = array_unique(array_filter(explode(',', $x['photos'])));
			if (is_array($temp) && count($temp)) {
				if ($this->developmentMode == TRUE) $temp = array_slice($temp, 0, 3);
				foreach ($temp as $key => $value) {
					$medium = \Service\Medium\Medium::createFromUrl('http://www.tralandia.com/u/'.$value);
					if ($medium) $attraction->addMedium($medium);
				}
			}

			$attraction->save();
		}

		$this->savedVariables['importedSections']['contactTypes'] = 1;

	}
}