<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
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

		$this->importContinents();
		$this->importCountries();
		//$this->importTravelings();
		//$this->importRegions();
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
		$locationType->save();


		$r = q('select * from continents order by id');
		while($x = mysql_fetch_array($r)) {
			$s = S\Location\LocationService::get();
			$s->name = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			$s->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$s->type = $locationType;
			$s->save();

			$s->createRoot();
			$this->continentsByOldId[$x['id']] = $s;
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
		$locationType->save();

		$r = q('select * from countries order by id');
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
			$country->pricesPizza = new \Extras\Types\Price($x['prices_pizza']);
			$country->pricesDinner = new \Extras\Types\Price($x['prices_dinner']);
			$country->airports = $x['airports']; // @todo - tu je chyba, pri pismene š to odreze, proste ako keby mal problem s diakritikou...

			$countryDetails = array();
			$countryDetails['beta'] = $x['beta'];
			$countryDetails['inEu'] = $x['in_eu'];

			$country->oldId = $x['id'];
			$country->details = $countryDetails;



			if (strlen($x['skype'])) $country->addContact($this->createContact('Skype', $x['skype']));
			if (strlen($x['phone'])) $country->addContact($this->createContact('Phone', $x['phone']));
			// $t = qNew('select id from domain where domain = "'.$x['domain'].'"');
			// $t = mysql_fetch_array($t);

			// if ($t[0] > 0) {
			// 	$thisDomain = \Services\DomainService::get($t[0]);
			// 	$country->addContact($this->createContact('Email', 'info@'.$thisDomain->domain));
			// }


			/*
				name - importujem z countries.name, a locative hladam v countries_translations, kde name = '' a name_locative mame, ak je done = 1 hned aj dame activated
				nameOfficial - hladam najdlhsi name v countries_synonyms, aj locative beriem odtialto
				nameShort - hladam najkratsi name v countries_synonyms, aj locative beriem odtialto
				VSETKO TOTO SA BUDE PREKLADAT DO INCOMINGLANGUAGES
			*/

			$namePhrase = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			// $r1 = q('select * from countries_translations where location_id = '.$x['id']);
			// while ($x1 = mysql_fetch_array($r1)) {				
			// }
			$location->name = $namePhrase;

			if ($x['name_long_dic_id'] > 0) {
				$location->nameOfficial = $this->createNewPhrase($this->dictionaryTypeNameOfficial, $x['name_long_dic_id']);
			}

			//$location->nameShort = NULL;

			$t = \Services\Dictionary\PhraseService::get($location->name); # @david - problem s ukladanim bez ID

			$location->slug = $namePhrase->getTranslation(getLangByIso('en'))->translation; // @david 
			
			$location->type = $locationType;
			$location->polygon = NULL;
			$location->latitude = new \Extras\Types\Latlong($x['latitude']);
			$location->longitude = new \Extras\Types\Latlong($x['longitude']);
			$location->defaultZoom = $x['default_zoom'];
			//$location->domain = $thisDomain;

			$location->country = $country; 

			// @david naschval som to dal na koniec!
			if(array_key_exists($x['continent'], $this->continentsByOldId)) {
				$continent = $this->continentsByOldId[$x['continent']];
				$continent->addChild($location);
			}


			$location->save();
			$country->save();
			//debug($location); debug($country); return;
		}		
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
}