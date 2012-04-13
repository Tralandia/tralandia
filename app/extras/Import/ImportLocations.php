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
	Service\Log\Change as ChangeLog;

class ImportLocations extends BaseImport {

	private $dictionaryTypeName;
	private $dictionaryTypeNameOfficial;
	private $dictionaryTypeNameShort;
	private $continentsByOldId = array();

	public function doImport($subsection) {
		
		$this->setSubsections('locations');

		$this->dictionaryTypeName = $this->createDictionaryType('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', array('locativeRequired' => TRUE));
		$this->dictionaryTypeNameOfficial = $this->createDictionaryType('\Location\Location', 'nameOfficial', 'supportedLanguages', 'NATIVE', array('locativeRequired' => TRUE));
		$this->dictionaryTypeNameShort = $this->createDictionaryType('\Location\Location', 'nameShort', 'supportedLanguages', 'NATIVE', array('locativeRequired' => TRUE));

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
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'world', $language);
		$locationType->slug = 'world';
		$locationType->save();
		$this->worldType = $locationType;

		// Create location World
		$namePhrase = \Service\Dictionary\Phrase::get();
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhrase->addTranslation($this->createTranslation($language, 'World'));
		$namePhrase->save();

		$s = \Service\Location\Location::get();
		$s->name = $namePhrase;
		$s->type = $this->worldType;
		$s->slug = 'world';
		$s->save();


		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'continent', $language);
		$locationType->slug = 'continent';
		$locationType->save();
		$this->continentsType = $locationType;

		$r = q('select * from continents order by id');
		while($x = mysql_fetch_array($r)) {
			$s = \Service\Location\Location::get();
			$s->name = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			$s->type = $locationType;
			$s->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$s->oldId = $x['id'];
			$s->save();

			$s->createRoot();
			//debug($s);
		}
	}

	// ----------------------------------------------------------
	// ------------- COUNTRIES
	// ----------------------------------------------------------
	private function importCountries() {

		$dictionaryType = $this->createDictionaryType('\Location\TypeService', 'name', 'supportedLanguages', 'ACTIVE');

		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createNewPhrase($dictionaryType, 865);
		$locationType->slug = 'country';
		$locationType->save();

		$r = q('select * from countries order by id');
		//$r = q('select * from countries where id = 46 order by id');
		while($x = mysql_fetch_array($r)) {
			$location = \Service\Location\Location::get();
			$country = \Service\Location\Country::get();

			$country->status = $x['supported'] == 1 ? 'supported' : ($x['status'] == 1 ? 'launched' : '');
			$country->oldId = $x['id'];
			$country->iso = $x['iso'];
			$country->iso3 = $x['iso3'];
			$country->defaultLanguage = \Service\Dictionary\Language::get(getByOldId('\Dictionary\Language', $x['default_language_id']));
			$t = getNewIds('\Dictionary\Language', $x['languages']);
			foreach ($t as $key => $value) {
				$t1 = \Service\Dictionary\Language::get($value);
				$country->addLanguage($t1);
			}

			$t = \Service\Currency::get(getByOldId('\Currency', $x['default_currency_id']));
			if ($t->id) {
				$country->defaultCurrency = $t;
			}
			
			$t = str_replace(',', '', $x['currencies']);
			$t1 = NULL;
			if (((int)$t) > 0) {
				$t1 = \Service\Currency::get(getByOldId('\Currency', (int)$t));
			} else if (strlen($t) == 3) {
				$t1 = getCurrencyByIso($t);
			}

			if (isset($t1->id) && $t1->id > 0) {
				$country->addCurrency($t1);
			}

			$country->population = $x['population'];
			$country->phonePrefix = $x['phone_prefix'];
			
			if (strlen($x['fb_group'])) $country->facebookGroup = $this->createContact('Url', $x['fb_group']);
			$country->capitalCity = $x['capital_city'];

			if (strlen($x['phone_number_emergency'])) $country->phoneNumberEmergency = $this->createContact('Phone', $x['phone_number_emergency']);
			if (strlen($x['phone_number_police'])) $country->phoneNumberPolice = $this->createContact('Phone', $x['phone_number_police']);
			if (strlen($x['phone_number_medical'])) $country->phoneNumberMedical = $this->createContact('Phone', $x['phone_number_medical']);
			if (strlen($x['phone_number_fire'])) $country->phoneNumberFire = $this->createContact('Phone', $x['phone_number_fire']);
			if (strlen($x['wikipedia_link'])) $country->wikipediaLink = $this->createContact('Url', $x['wikipedia_link']);

			$country->drivingSide = $x['driving_side'];
			$country->pricesPizza = new Price($x['prices_pizza']); // @todo - spravit menu, aby som posielal ako entitu / servicu
			$country->pricesDinner = new Price($x['prices_dinner']);
			$country->airports = $x['airports'];

			$countryDetails = array();
			$countryDetails['beta'] = $x['beta'];
			$countryDetails['inEu'] = $x['in_eu'];

			$country->details = $countryDetails;

			if (strlen($x['skype'])) $country->addContact($this->createContact('Skype', $x['skype']));
			if (strlen($x['phone'])) $country->addContact($this->createContact('Phone', $x['phone']));

			if (strlen($x['domain'])) {
				$country->addContact($this->createContact('Email', 'info@'.$x['domain']));
			}

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
			}

			//$location->nameShort = NULL;

			$location->type = $locationType;
			$location->slug = $namePhrase->getTranslation(\Service\Dictionary\Language::getByIso('en'))->translation;
			
			$location->polygon = NULL;
			$location->latitude = new Latlong($x['latitude']);
			$location->longitude = new Latlong($x['longitude']);
			$location->defaultZoom = $x['default_zoom'];

			$t = mysql_fetch_array(qNew('select id from location_location where oldId = '.$x['continent']));
			$location->parentId = $t[0];

			if ($x['domain']) $location->domain = \Service\Domain::getByDomain($x['domain']);

			$location->country = $country;
			//debug($location->parentId); return;
			//debug($location); debug($country); return;

			$country->save();
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
		$countriesByOld = getNewIdsByOld('\Location\Country');
		$r = q('select * from countries_traveling order by id');
		while ($x = mysql_fetch_array($r)) {
			$sourceCountry = \Service\Location\Country::get($countriesByOld[$x['source_country_id']]);
			$destinationCountry = \Service\Location\Country::get($countriesByOld[$x['destination_country_id']]);
			$traveling = \Service\Location\Traveling::get();
			$traveling->sourceLocation = $sourceCountry->location;
			$traveling->destinationLocation = $destinationCountry->location;
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
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'region', \Service\Dictionary\Language::getByIso('en'));
		$locationType->slug = 'region';
		$locationType->save();
		$this->regionsType = $locationType;

		//$r = q('select * from regions order by id');
		$r = q('select * from regions where country_id = 46 and level = 0 order by id');
		//$r = q('select * from regions where id = 48978 order by id');
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
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parentId = \Service\Location\Country::getByOldId($x['country_id'])->location->id;
			//debug($location); return;
			$location->save();
		}
	}

	// ----------------------------------------------------------
	// ------------- Administrative Regions
	// ----------------------------------------------------------
	private function importAdministrativeRegions1() {

		// Level 1

		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'administrativeRegionLevelOne', \Service\Dictionary\Language::getByIso('en'));
		$locationType->slug = 'administrativeRegionLevelOne';
		$locationType->save();
		$this->administrativeRegions1Type = $locationType;

		//$r = q('select * from regions order by id');
		$r = q('select * from regions where country_id = 46 and level = 1 order by id');
		//$r = q('select * from regions where id = 48978 order by id');
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
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parentId = \Service\Location\Country::getByOldId($x['country_id'])->location->id;
			$location->save();
		}

	}

	private function importAdministrativeRegions2() {
		// Level 2

		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'administrativeRegionLevelTwo', \Service\Dictionary\Language::getByIso('en'));
		$locationType->slug = 'administrativeRegionLevelTwo';
		$locationType->save();
		$this->administrativeRegions2Type = $locationType;

		$this->administrativeRegions1Type = \Service\Location\Type::getBySlug('administrativeregionlevelone');

		//$r = q('select * from regions order by id');
		$r = q('select * from regions where country_id = 46 and level = 2 order by id');
		//$r = q('select * from regions where id = 48978 order by id');
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
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			//debug($x['parent_id']); debug($this->administrativeRegions1Type); return;

			$t = \Service\Location\Location::getByOldIdAndType($x['parent_id'], $this->administrativeRegions1Type);
			if ($t) {
				$location->parentId = $t->id;
			} else {
				AP::addTask('\Location\Location - Level2HasNoParent', 
					array(
						'userCountry' => \Service\Location\Country::getByOldId($x['country_id'])->location->id,
						'userRole' => \Service\User\Role::getBySlug('manager'),
					),
					array(
						'location' => $location,
					)
				);
			}
			$location->save();
		}

	}

	// ----------------------------------------------------------
	// ------------- Localities
	// ----------------------------------------------------------
	private function importLocalities() {

		$locationType = \Service\Location\Type::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'locality', \Service\Dictionary\Language::getByIso('en'));
		$locationType->slug = 'locality';
		$locationType->save();
		$this->localitiesType = $locationType;

		//$r = q('select * from localities order by id');
		$r = q('select * from localities where country_id = 46 order by id');
		//$r = q('select * from localities where id = 48978 order by id');
		while($x = mysql_fetch_array($r)) {
			$location = \Service\Location\Location::get();

			$namePhrase = \Service\Dictionary\Phrase::get();
			$namePhrase->type = $this->dictionaryTypeName;
			$namePhrase->ready = TRUE;

			$country = \Service\Location\Country::getByOldId($x['country_id']);
			$r1 = q('select * from localities_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array(
					'locative' => $x1['name_locative'],
				);
				$t = $this->createTranslation(\Service\Dictionary\Language::getByOldId($x1['language_id']), $x['name'], $variations);
				$namePhrase->addTranslation($t);
			}

			$location->name = $namePhrase;
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parentId = $country->location->id;
			$location->save();
			//debug($location); return;
		}
	}

}