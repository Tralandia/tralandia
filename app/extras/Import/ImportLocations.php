<?php

/*
 * @todo:
 * tu zatial vsade importujem len SK regiony / obce, bude sa to musiet upravit pred finalnym importom
*/
namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Extras\Types\Price,
	Extras\Types\Latlong,
	Service\Autopilot\Autopilot as AP,
	Service\Log as SLog;

class ImportLocations extends BaseImport {

	private $dictionaryTypeName;
	private $dictionaryTypeNameOfficial;
	private $dictionaryTypeNameShort;
	private $continentsByOldId = array();

	public function doImport($subsection) {
		
		$this->setSubsections('locations');

		$this->dictionaryTypeName = $this->createDictionaryType('\Location\Location', 'name', 'NATIVE', array('locativesRequired' => TRUE));
		$this->dictionaryTypeNameOfficial = $this->createDictionaryType('\Location\Location', 'nameOfficial', 'NATIVE', array('locativesRequired' => TRUE));
		$this->dictionaryTypeNameShort = $this->createDictionaryType('\Location\Location', 'nameShort', 'NATIVE', array('locativesRequired' => TRUE));
		\Extras\Models\Service::flush(FALSE);

		debug($subsection);
		$this->$subsection();

		$this->savedVariables['importedSubSections']['locations'][$subsection] = 1;

		if (end($this->sections['locations']['subsections']) == $subsection) {
			$this->savedVariables['importedSections']['locations'] = 1;		
		}
	}

	// ----------------------------------------------------------
	// ------------- CONTINENTS
	// ----------------------------------------------------------
	private function importContinents() {
		$language = \Service\Dictionary\Language::getByIso('en');

		// Create location type for world
		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'world', $language);
		$locationType->slug = 'world';
		$locationType->primary = TRUE;
		$locationType->save();
		$this->worldType = $locationType;

		// Create location World
		$namePhrase = \Service\Dictionary\Phrase::get();
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhrase->addTranslation($this->createTranslation($language, 'World'));
		$namePhrase->save();

		$s = \Service\Location\Location::get();
		$s->name = $namePhrase;
		$s->nameShort = \Service\Dictionary\Phrase::get();
		$s->nameOfficial = \Service\Dictionary\Phrase::get();
		$s->type = $this->worldType;
		$s->slug = 'world';
		$s->save();


		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'continent', $language);
		$locationType->slug = 'continent';
		$locationType->primary = TRUE;
		$locationType->save();
		$this->continentsType = $locationType;

		$r = q('select * from continents order by id');
		while($x = mysql_fetch_array($r)) {
			$s = \Service\Location\Location::get();
			$s->name = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			$s->nameShort = \Service\Dictionary\Phrase::get();
			$s->nameOfficial = \Service\Dictionary\Phrase::get();
			$s->type = $locationType;
			$s->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$s->oldId = $x['id'];
			$s->save();
			//debug($s);
		}
	}

	// ----------------------------------------------------------
	// ------------- COUNTRIES
	// ----------------------------------------------------------
	private function importCountries() {

		$dictionaryType = $this->createDictionaryType('\Location\TypeService', 'name', 'ACTIVE');

		$locationTypeCountry = \Service\Location\Type::get();
		$locationTypeCountry->name = $this->createNewPhrase($dictionaryType, 865);
		$locationTypeCountry->slug = 'country';
		$locationTypeCountry->save();

		$locationTypeState = \Service\Location\Type::get();
		$locationTypeState->name = $this->createNewPhrase($dictionaryType, 865);
		$locationTypeState->slug = 'state';
		$locationTypeState->save();

		// Create USA
		$namePhrase = \Service\Dictionary\Phrase::get();
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhrase->addTranslation($this->createTranslation($language, 'USA'));
		$namePhrase->save();

		$s = \Service\Location\Location::get();
		$s->name = $namePhrase;
		$s->nameShort = \Service\Dictionary\Phrase::get();
		$s->nameOfficial = \Service\Dictionary\Phrase::get();
		$s->type = $locationTypeCountry;
		$s->parent = 5; // north america
		$s->slug = 'usa';
		$s->save();

		$usa = $s;

		// Create Canada
		$namePhrase = \Service\Dictionary\Phrase::get();
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhrase->addTranslation($this->createTranslation($language, 'Canada'));
		$namePhrase->save();

		$s = \Service\Location\Location::get();
		$s->name = $namePhrase;
		$s->nameShort = \Service\Dictionary\Phrase::get();
		$s->nameOfficial = \Service\Dictionary\Phrase::get();
		$s->type = $locationTypeCountry;
		$s->parent = 5; // north america
		$s->slug = 'canada';
		$s->save();

		$canada = $s;


		$r = q('select * from countries order by id');
		//$r = q('select * from countries where id = 46 order by id');
		while($x = mysql_fetch_array($r)) {
			$location = \Service\Location\Location::get();

			$location->status = $x['supported'] == 1 ? 'supported' : ($x['status'] == 1 ? 'launched' : '');
			$location->oldId = $x['id'];
			$location->iso = $x['iso'];
			$location->iso3 = $x['iso3'];
			$location->defaultLanguage = \Service\Dictionary\Language::get(getByOldId('\Dictionary\Language', $x['default_language_id']));
			$t = getNewIds('\Dictionary\Language', $x['languages']);
			foreach ($t as $key => $value) {
				$t1 = \Service\Dictionary\Language::get($value);
				$location->addLanguage($t1);
			}

			$t = \Service\Currency::get(getByOldId('\Currency', $x['default_currency_id']));
			if ($t->id) {
				$location->defaultCurrency = $t;
			}
			
			$t = str_replace(',', '', $x['currencies']);
			$t1 = NULL;
			if (((int)$t) > 0) {
				$t1 = \Service\Currency::get(getByOldId('\Currency', (int)$t));
			} else if (strlen($t) == 3) {
				$t1 = getCurrencyByIso($t);
			}

			if (isset($t1->id) && $t1->id > 0) {
				$location->addCurrency($t1);
			}

			$location->population = $x['population'];
			$location->phonePrefix = $x['phone_prefix'];
			
			if (strlen($x['fb_group'])) $location->facebookGroup = new \Extras\Types\Url($x['fb_group']);
			$location->capitalCity = $x['capital_city'];

			if (strlen($x['phone_number_emergency'])) $location->phoneNumberEmergency = new \Extras\Types\Phone($x['phone_number_emergency']);
			if (strlen($x['phone_number_police'])) $location->phoneNumberPolice = new \Extras\Types\Phone($x['phone_number_police']);
			if (strlen($x['phone_number_medical'])) $location->phoneNumberMedical = new \Extras\Types\Phone($x['phone_number_medical']);
			if (strlen($x['phone_number_fire'])) $location->phoneNumberFire = new \Extras\Types\Phone($x['phone_number_fire']);
			if (strlen($x['wikipedia_link'])) $location->wikipediaLink = new \Extras\Types\Url($x['wikipedia_link']);

			$location->drivingSide = $x['driving_side'];
			$location->pricesPizza = new Price($x['prices_pizza']); // @todo - spravit menu, aby som posielal ako entitu / servicu
			$location->pricesDinner = new Price($x['prices_dinner']);
			$location->airports = $x['airports'];

			$countryDetails = array();
			$countryDetails['beta'] = $x['beta'];
			$countryDetails['inEu'] = $x['in_eu'];

			$location->details = $countryDetails;

			$contacts = new \Extras\Types\Contacts();


			if (strlen($x['skype'])) $contacts->add(new \Extras\Types\Skype($x['skype']));

			if (strlen($x['phone'])) $contacts->add(new \Extras\Types\Phone($x['phone']));

			if (strlen($x['domain'])) {
				$contacts->add(new \Extras\Types\Email('info@'.$x['domain']));
			}

			$location->contacts = $contacts;

			/*
				name - importujem z countries.name, a locative hladam v countries_translations, kde name = '' a name_locative mame, ak je done = 1 hned aj dame activated
				nameOfficial - hladam najdlhsi name v countries_synonyms, aj locative beriem odtialto
				nameShort - hladam najkratsi name v countries_synonyms, aj locative beriem odtialto
				VSETKO TOTO SA BUDE PREKLADAT DO INCOMINGLANGUAGES
			*/

			$namePhrase = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			$r1 = q('select * from countries_translations where location_id = '.$x['id']);
			//$r1 = q('select * from countries_translations where location_id = 46');
			while ($x1 = mysql_fetch_array($r1)) {
				$t = $namePhrase->getTranslation(\Service\Dictionary\Language::getByOldId($x1['language_id']));
				$variations = array(
					'translation' => $x1['name'],
					'locative' => $x1['name_locative'],
				);
				$t->variations = $variations;
			}

			$location->name = $namePhrase;

			if ($x['name_long_dic_id'] > 0) {

				$nameOfficialPhrase = $this->createNewPhrase($this->dictionaryTypeNameOfficial, $x['name_long_dic_id']);
				
				$t = $nameOfficialPhrase->getTranslations();
				foreach ($t as $key => $value) {
					$value = \Service\Dictionary\Translation::get($value);
					$language = \Service\Dictionary\Language::get($value->language);
					$x1 = qf('select * from countries_synonyms where country_id = '.$x['id'].' and language_id = '.$language->oldId.' order by length(name) DESC limit 1');
					$value->translation = $x1['name'];
					$variations = array(
						'translation' => $x1['name'],
						'locative' => $x1['name_locative'],
					);
					$value->variations = $variations;
					//debug($value);
					$value->save();
				}

				$location->nameOfficial = $nameOfficialPhrase;
			} else {
				$location->nameOfficial = \Service\Dictionary\Phrase::get();				
			}

			$location->nameShort = \Service\Dictionary\Phrase::get();

			$location->type = $locationType;
			$location->slug = $namePhrase->getTranslation(\Service\Dictionary\Language::getByIso('en'))->translation;
			
			$location->polygon = NULL;
			$location->latitude = new Latlong($x['latitude']);
			$location->longitude = new Latlong($x['longitude']);
			$location->defaultZoom = $x['default_zoom'];

			if (strlen($x['iso']) == 4) {
				if (substr($x['iso'], 0, 2) == 'us') {
					$location->parent = $usa->id;
				} else if (substr($x['iso'], 0, 2) == 'ca') {
					$location->parent = $canada->id;
				}
			} else {
				$t = mysql_fetch_array(qNew('select id from location_location where oldId = '.$x['continent']));
				$location->parent = $t[0];
			}

			if ($x['domain']) $location->domain = \Service\Domain::getByDomain($x['domain']);

			//debug($location->parent); return;
			//debug($location); return;

			$location->save();
		}
	}

	// ----------------------------------------------------------
	// ------------- COUNTRIES Update Nested Set
	// ----------------------------------------------------------
	private function updateNestedSetCountries() {
/*		$continents = new \Service\Location\LocationList::getByType();
		if(array_key_exists($x['continent'], $this->continentsByOldId)) {
			$continent = $this->continentsByOldId[$x['continent']];
			$continent->addChild($location);
		}
*/
	}

	// ----------------------------------------------------------
	// ------------- COUNTRIES Travelings
	// ----------------------------------------------------------
	private function importTravelings() {
		$countryLocationType = \Service\Location\Type::getBySlug('country');

		$r = q('select * from countries_traveling order by id');
		while ($x = mysql_fetch_array($r)) {
			$traveling = \Service\Location\Traveling::get();
			$traveling->sourceLocation = \Service\Location\Location::getByTypeAndOldId($countryLocationType, $x['source_country_id']);
			$traveling->destinationLocation = \Service\Location\Location::getByTypeAndOldId($countryLocationType, $x['destination_country_id']);
			$traveling->peopleCount = $x['people_count'];
			$traveling->year = $x['year'];
			$traveling->oldId = $x['id'];
			$traveling->save();
		}
		return false;
	}

	// ----------------------------------------------------------
	// ------------- Regions Level 0
	// ----------------------------------------------------------
	private function importRegions() {
		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'region', \Service\Dictionary\Language::getByIso('en'));
		$locationType->slug = 'region';
		$locationType->save();
		$this->regionsType = $locationType;

		$countryLocationType = \Service\Location\Type::getBySlug('country');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from regions where country_id = 46 and level = 0 order by id');
		} else {
			$r = q('select * from regions where level = 0 order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$location = \Service\Location\Location::get();

			$namePhrase = \Service\Dictionary\Phrase::get();
			$namePhrase->type = $this->dictionaryTypeName;
			$namePhrase->ready = TRUE;

			$r1 = q('select * from regions_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array(
					'locative' => $x1['name_locative'],
				);
				$t = $this->createTranslation(\Service\Dictionary\Language::getByOldId($x1['language_id']), $x['name'], $variations);
				$namePhrase->addTranslation($t);
			}

			$location->name = $namePhrase;
			$location->nameOfficial = $namePhrase;
			$location->nameShort = $namePhrase;
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parent = \Service\Location\Location::getByOldIdAndType($x['country_id'], $countryLocationType)->id;
			//debug($location); return;
			$location->save();
		}
	}

	// ----------------------------------------------------------
	// ------------- Administrative Regions
	// ----------------------------------------------------------
	private function importAdministrativeRegions1() {

		// Level 1

		$locationType = \Service\Location\Type::getBySlug('region');


		$countryLocationType = \Service\Location\Type::getBySlug('country');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from regions where country_id = 46 and level = 1 order by id');
		} else {
			$r = q('select * from regions where level = 1 order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$location = \Service\Location\Location::get();

			$namePhrase = \Service\Dictionary\Phrase::get();
			$namePhrase->type = $this->dictionaryTypeName;
			$namePhrase->ready = TRUE;

			$r1 = q('select * from regions_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array(
					'locative' => $x1['name_locative'],
				);
				$t = $this->createTranslation(\Service\Dictionary\Language::getByOldId($x1['language_id']), $x['name'], $variations);
				$namePhrase->addTranslation($t);
			}

			$location->name = $namePhrase;
			$location->nameOfficial = $namePhrase;
			$location->nameShort = $namePhrase;
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parent = \Service\Location\Location::getByOldIdAndType($x['country_id'], $countryLocationType)->id;
			$location->save();
		}

	}

	private function importAdministrativeRegions2() {
		// Level 2

		$locationType = \Service\Location\Type::getBySlug('region');

		$countryLocationType = \Service\Location\Type::getBySlug('country');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from regions where country_id = 46 and level = 2 order by id');
		} else {
			$r = q('select * from regions where level = 2 order by id');
		}
		while($x = mysql_fetch_array($r)) {
			$location = \Service\Location\Location::get();

			$namePhrase = \Service\Dictionary\Phrase::get();
			$namePhrase->type = $this->dictionaryTypeName;
			$namePhrase->ready = TRUE;

			$r1 = q('select * from regions_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array(
					'locative' => $x1['name_locative'],
				);
				$t = $this->createTranslation(\Service\Dictionary\Language::getByOldId($x1['language_id']), $x['name'], $variations);
				$namePhrase->addTranslation($t);
			}

			$location->name = $namePhrase;
			$location->nameOfficial = $namePhrase;
			$location->nameShort = $namePhrase;
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			//debug($x['parent_id']); debug($this->administrativeRegions1Type); return;

			$t = \Service\Location\Location::getByOldIdAndType($x['country_id'], $locationType);
			if ($t) {
				$location->parent = $t->id;
			}
			$location->save();
		}

	}

	// ----------------------------------------------------------
	// ------------- Localities
	// ----------------------------------------------------------
	private function importLocalities() {

		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'locality', \Service\Dictionary\Language::getByIso('en'));
		$locationType->slug = 'locality';
		$locationType->save();
		$this->localitiesType = $locationType;

		$countryLocationType = \Service\Location\Type::getBySlug('country');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from localities where country_id = 46 order by id');
		} else {
			$r = q('select * from localities order by id');
		}
		while($x = mysql_fetch_array($r)) {
			$location = \Service\Location\Location::get();

			$namePhrase = \Service\Dictionary\Phrase::get();
			$namePhrase->type = $this->dictionaryTypeName;
			$namePhrase->ready = TRUE;

			$countryLocation = \Service\Location\Location::getByOldIdAndType($x['country_id'], $countryLocationType);
			$r1 = q('select * from localities_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array(
					'locative' => $x1['name_locative'],
				);
				$t = $this->createTranslation(\Service\Dictionary\Language::getByOldId($x1['language_id']), $x['name'], $variations);
				$namePhrase->addTranslation($t);
			}

			$location->name = $namePhrase;
			$location->nameOfficial = $namePhrase;
			$location->nameShort = $namePhrase;
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parent = $countryLocation->id;
			$location->save();
			//debug($location); return;
		}
	}

}