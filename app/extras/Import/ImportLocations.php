<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Extras\Types\Price,
	Extras\Types\Latlong,
	Services\Dictionary as D,
	Services as S,
	Services\Log\Change as SLog;

class ImportLocations extends BaseImport {

	private $dictionaryTypeName;
	private $dictionaryTypeNameOfficial;
	private $dictionaryTypeNameShort;
	private $continentsByOldId = array();

	public function doImport() {
		$this->savedVariables['importedSections']['locations'] = 1;

		$this->dictionaryTypeName = $this->createDictionaryType('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', array('locativeRequired' => TRUE));
		$this->dictionaryTypeNameOfficial = $this->createDictionaryType('\Location\Location', 'nameOfficial', 'supportedLanguages', 'NATIVE', array('locativeRequired' => TRUE));
		$this->dictionaryTypeNameShort = $this->createDictionaryType('\Location\Location', 'nameShort', 'supportedLanguages', 'NATIVE', array('locativeRequired' => TRUE));

		//$this->importContinents();
		//$this->importCountries();
		//$this->updateNestedSetCountries();
		//$this->importTravelings();
		$this->importRegions();
		//$this->importLocalities();

		$this->savedVariables['importedSections']['locations'] = 2;

	}

	// ----------------------------------------------------------
	// ------------- CONTINENTS
	// ----------------------------------------------------------
	private function importContinents() {
		$language = getLangByIso('en');
		$locationType = S\Location\TypeService::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'continent', $language);
		$locationType->slug = 'continent';
		$locationType->save();
		$this->continentsType = $locationType;

		$r = q('select * from continents order by id');
		while($x = mysql_fetch_array($r)) {
			$s = S\Location\LocationService::get();
			$s->name = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			$s->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$s->type = $locationType;
			$s->oldId = $x['id'];
			$s->save();

			$s->createRoot();
			//debug($s);
		}

		Service::flush(FALSE);
	}

	// ----------------------------------------------------------
	// ------------- COUNTRIES
	// ----------------------------------------------------------
	private function importCountries() {

		$dictionaryType = $this->createDictionaryType('\Location\TypeService', 'name', 'supportedLanguages', 'ACTIVE');

		$locationType = S\Location\TypeService::get();
		$locationType->name = $this->createNewPhrase($dictionaryType, 865);
		$locationType->slug = 'country';
		$locationType->save();

		$r = q('select * from countries order by id');
		//$r = q('select * from countries where id = 46 order by id');
		while($x = mysql_fetch_array($r)) {
			$location = S\Location\LocationService::get();
			$country = S\Location\CountryService::get();

			$country->status = $x['supported'] == 1 ? 'supported' : ($x['status'] == 1 ? 'launched' : '');
			$country->oldId = $x['id'];
			$country->iso = $x['iso'];
			$country->iso3 = $x['iso3'];
			$country->defaultLanguage = \Services\Dictionary\LanguageService::get(getByOldId('\Dictionary\Language', $x['default_language_id']));
			$t = getNewIds('\Dictionary\Language', $x['languages']);
			foreach ($t as $key => $value) {
				$t1 = \Services\Dictionary\LanguageService::get($value);
				$country->addLanguage($t1);
			}

			$t = \Services\CurrencyService::get(getByOldId('\Currency', $x['default_currency_id']));
			if ($t->id) {
				$country->defaultCurrency = $t;
			}
			
			$t = str_replace(',', '', $x['currencies']);
			$t1 = NULL;
			if (((int)$t) > 0) {
				$t1 = \Services\CurrencyService::get(getByOldId('\Currency', (int)$t));
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

			$country->phoneNumberEmergency = $this->createContact('Phone', $x['phone_number_emergency']);
			$country->phoneNumberPolice = $this->createContact('Phone', $x['phone_number_police']);
			$country->phoneNumberMedical = $this->createContact('Phone', $x['phone_number_medical']);
			$country->phoneNumberFire = $this->createContact('Phone', $x['phone_number_fire']);
			$country->wikipediaLink = $this->createContact('Url', $x['wikipedia_link']);

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
				$t = $namePhrase->getTranslation($this->languagesByOldId[$x1['language_id']]);
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
					$value = \Services\Dictionary\TranslationService::get($value);
					$language = \Services\Dictionary\LanguageService::get($value->language);
					$x1 = qf('select * from countries_synonyms where country_id = '.$x['id'].' and language_id = '.$language->oldId.' order by length(name) DESC limit 1');
					$value->translation = $x1['name'];
					$variations = array(
						'translation' => $x1['name'],
						'locative' => $x1['name_locative'],
					);
					$value->variations = $variations;
					$value->save();
				}

				$location->nameOfficial = $nameOfficialPhrase;
			}

			//$location->nameShort = NULL;

			$location->slug = $namePhrase->getTranslation(\Services\Dictionary\LanguageService::getByIso('en'))->translation;
			
			$location->type = $locationType;
			$location->polygon = NULL;
			$location->latitude = new Latlong($x['latitude']);
			$location->longitude = new Latlong($x['longitude']);
			$location->defaultZoom = $x['default_zoom'];

			$t = mysql_fetch_array(qNew('select id from location_location where oldId = '.$x['continent']));
			$location->parentId = $t[0];

			if ($x['domain']) $location->domain = \Services\DomainService::getByDomain($x['domain']);

			$location->country = $country;
			//debug($location->parentId); return;
			//debug($location); debug($country); return;

			$location->save();
			$country->save();
		}
	}

	// ----------------------------------------------------------
	// ------------- COUNTRIES Update Nested Set
	// ----------------------------------------------------------
	private function updateNestedSetCountries() {
/*		$continents = new \Services\Location\LocationList::getByType();
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
			$sourceCountry = \Services\Location\CountryService::get($countriesByOld[$x['source_country_id']]);
			$destinationCountry = \Services\Location\CountryService::get($countriesByOld[$x['destination_country_id']]);
			$traveling = \Services\Location\TravelingService::get();
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
	// ------------- Regions
	// ----------------------------------------------------------
	private function importRegions() {
		$locationType = S\Location\TypeService::get();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'supportedLanguages', 'NATIVE', 'region', \Services\Dictionary\Language::getByIso('en'));
		$locationType->slug = 'region';
		$locationType->save();
		$this->regionsType = $locationType;

		//$r = q('select * from regions order by id');
		//$r = q('select * from regions where country_id = 46 and level = 0 order by id');
		$r = q('select * from regions where id = 48978 order by id');
		while($x = mysql_fetch_array($r)) {
			$location = S\Location\LocationService::get();

			$namePhrase = \Services\Dictionary\PhraseService::get();
			$namePhrase->type = $this->dictionaryTypeName;
			$namePhrase->ready = TRUE;

			$r1 = q('select * from regions_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array(
					'locative' => $x1['name_locative'],
				);
				$t = $this->createTranslation(\Services\Dictionary\Language::getByOldId($x1['language_id']), $x['name'], $variations);
				$namePhrase->addTranslation($t);
			}


			$location->name = $namePhrase;
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parentId = \Services\Location\CountryService::getByOldId($x['country_id'])->oldId;
			debug($location); return;
			$location->save();
			return; 
			//debug($s);
		}

		Service::flush(FALSE);
	}

}