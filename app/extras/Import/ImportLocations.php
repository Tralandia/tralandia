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
	Extras\Types\Json,
	Service\Autopilot\Autopilot as AP,
	Service\Log as SLog;

class ImportLocations extends BaseImport {

	private $dictionaryTypeName;
	private $dictionaryTypeNameOfficial;
	private $dictionaryTypeNameShort;
	private $continentsByOldId = array();

	public function doImport($subsection) {
		

		$this->dictionaryTypeName = $this->createPhraseType('\Location\Location', 'name', 'NATIVE');
		$this->dictionaryTypeNameOfficial = $this->createPhraseType('\Location\Location', 'nameOfficial', 'NATIVE');
		$this->dictionaryTypeNameShort = $this->createPhraseType('\Location\Location', 'nameShort', 'NATIVE');

		$this->{$subsection}();

	}

	// ----------------------------------------------------------
	// ------------- CONTINENTS
	// ----------------------------------------------------------
	private function importContinents() {
		$language = $this->context->languageRepositoryAccessor->get()->findOneBy(array('iso' => 'en'));

		// Create location type for world
		$locationType = $this->context->locationTypeEntityFactory->create();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'world', $language);
		$locationType->slug = 'world';
		$locationType->primary = TRUE;
		
		$this->model->persist($locationType);
		$this->model->flush();

		$worldType = $locationType;

		$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew();
		$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhraseService->createTranslation($language, 'World');
		$this->model->persist($namePhrase);
		$this->model->flush();

		// Create location World
		$s = $this->context->locationEntityFactory->create();
		$s->name = $namePhrase;
		$s->nameShort = $this->context->phraseEntityFactory->create();
		$s->nameOfficial = $this->context->phraseEntityFactory->create();
		$s->type = $worldType;
		$s->slug = 'world';
		$this->model->persist($s);
		$this->model->flush();


		$locationType = $this->context->locationTypeEntityFactory->create();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'continent', $language);
		$locationType->slug = 'continent';
		$locationType->primary = TRUE;
		$this->model->persist($locationType);
		$this->model->flush();

		$r = q('select * from continents order by id');
		while($x = mysql_fetch_array($r)) {
			$s = $this->context->locationEntityFactory->create();
			$s->name = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			$s->nameShort = $this->context->phraseEntityFactory->create();
			$s->nameOfficial = $this->context->phraseEntityFactory->create();
			$s->type = $locationType;
			$s->slug = qc('select text from z_en where id = '.$x['name_dic_id']);
			$s->oldId = $x['id'];
			$this->model->persist($s);
			//debug($s);
		}
		$this->model->flush();
	}

	// ----------------------------------------------------------
	// ------------- COUNTRIES
	// ----------------------------------------------------------
	private function importCountries() {

		$language = $this->context->languageRepositoryAccessor->get()->findOneBy(array('iso' => 'en'));
		$dictionaryType = $this->createPhraseType('\Location\TypeService', 'name', 'ACTIVE');

		$locationTypeCountry = $this->context->locationTypeEntityFactory->create();
		$locationTypeCountry->name = $this->createNewPhrase($dictionaryType, 865);
		$locationTypeCountry->slug = 'country';
		$this->model->persist($locationTypeCountry);

		$locationTypeState = $this->context->locationTypeEntityFactory->create();
		$locationTypeState->name = $this->createNewPhrase($dictionaryType, 865);
		$locationTypeState->slug = 'state';
		$this->model->persist($locationTypeState);

		// Create USA
		$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew();
		$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhraseService->createTranslation($language, 'USA');
		$this->model->persist($namePhrase);

		$s = $this->context->locationEntityFactory->create();
		$s->name = $namePhrase;
		$s->nameShort = $this->context->phraseEntityFactory->create();
		$s->nameOfficial = $this->context->phraseEntityFactory->create();
		$s->type = $locationTypeCountry;
		$s->parent = $this->context->locationRepositoryAccessor->get()->find(5); // north america
		$s->slug = 'usa';
		$s->iso = 'us';
		$s->defaultLanguage = $language;
		$this->model->persist($s);

		$usa = $s;

		// Create Canada
		$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew();
		$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhraseService->createTranslation($language, 'Canada');
		$this->model->persist($namePhrase);

		$s = $this->context->locationEntityFactory->create();
		$s->name = $namePhrase;
		$s->nameShort = $this->context->phraseEntityFactory->create();
		$s->nameOfficial = $this->context->phraseEntityFactory->create();
		$s->type = $locationTypeCountry;
		$s->parent = $this->context->locationRepositoryAccessor->get()->find(5); // north america
		$s->slug = 'canada';
		$s->iso = 'ca';
		$s->defaultLanguage = $language;
		$this->model->persist($s);

		$canada = $s;

		// Create Australia
		$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew();
		$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhraseService->createTranslation($language, 'Australia');
		$this->model->persist($namePhrase);

		$s = $this->context->locationEntityFactory->create();
		$s->name = $namePhrase;
		$s->nameShort = $this->context->phraseEntityFactory->create();
		$s->nameOfficial = $this->context->phraseEntityFactory->create();
		$s->type = $locationTypeCountry;
		$s->parent = $this->context->locationRepositoryAccessor->get()->find(5); // north america
		$s->slug = 'australia';
		$s->iso = 'au';
		$s->defaultLanguage = $language;
		$this->model->persist($s);

		$australia = $s;

		$this->model->flush();

		$r = q('select * from countries order by id');
		//$r = q('select * from countries where id = 46 order by id');
		while($x = mysql_fetch_array($r)) {
			$location = $this->context->locationEntityFactory->create();

			$location->oldId = $x['id'];
			$location->iso = $x['iso'];
			$location->iso3 = $x['iso3'];
			$location->isPrimary = TRUE;

			if ($x['default_language_id'] > 0) {
				$location->defaultLanguage = $this->context->languageRepositoryAccessor->get()->find(getByOldId('\Language', $x['default_language_id']));
			} else {
				$location->defaultLanguage = $this->context->languageRepositoryAccessor->get()->find(getByOldId('\Language', 38));
			}

			$t = $this->context->currencyRepositoryAccessor->get()->findOneBy(array('oldId' => $x['default_currency_id']));
			if ($t) {
				$location->defaultCurrency = $t;
			}
			
			$countryDetails = array();
			$countryDetails['beta'] = $x['beta'];
			$countryDetails['inEu'] = $x['in_eu'];

			$location->details = $countryDetails;

			$namePhrase = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
			$r1 = q('select * from countries_translations where location_id = '.$x['id']);
			//$r1 = q('select * from countries_translations where location_id = 46');
			while ($x1 = mysql_fetch_array($r1)) {
				$languageTemp = $this->context->languageRepositoryAccessor->get()->findOneBy(array('oldId' => $x1['language_id']));
				$t = $namePhraseService->getTranslation($languageTemp);
				$variations = array();
				$variations[0][0] = array(
					'nominative' => $x1['name'],
					'locative' => $x1['name_locative'],
				);
				$t->updateVariations($variations);
			}

			$location->name = $namePhrase;

			if ($x['name_long_dic_id'] > 0) {

				$nameOfficialPhrase = $this->createNewPhrase($this->dictionaryTypeNameOfficial, $x['name_long_dic_id']);
				
				$t = $nameOfficialPhrase->getTranslations();
				foreach ($t as $key => $value) {
					$language = $value->language;
					$x1 = qf('select * from countries_synonyms where country_id = '.$x['id'].' and language_id = '.$language->oldId.' order by length(name) DESC limit 1');
					$value->translation = $x1['name'];
					$variations = array();
					$variations[0][0] = array(
						'nominative' => $x1['name'],
						'locative' => $x1['name_locative'],
					);
					$value->updateVariations($variations);
					//debug($value);
					$this->model->persist($value);
				}

				$location->nameOfficial = $nameOfficialPhrase;
			} else {
				$location->nameOfficial = $this->context->phraseEntityFactory->create();				
			}

			$location->nameShort = $this->context->phraseEntityFactory->create();

			$location->type = $locationTypeCountry;
			$languageTemp = $this->context->languageRepositoryAccessor->get()->findOneBy(array('iso' => 'en'));
			$location->slug = $namePhraseService->getTranslation($languageTemp)->translation;
			
			$location->polygons = NULL;
			if ($x['latitude'] != 0) $location->latitude = new Latlong($x['latitude']);
			if ($x['longitude'] != 0) $location->longitude = new Latlong($x['longitude']);
			$location->phonePrefix = $x['phone_prefix'];
			$location->defaultZoom = $x['default_zoom'];

			if (strlen($x['iso']) == 4) {
				if (substr($x['iso'], 0, 2) == 'us') {
					$location->parent = $usa;
					$location->type = $locationTypeState;
				} else if (substr($x['iso'], 0, 2) == 'ca') {
					$location->parent = $canada;
					$location->type = $locationTypeState;
				} else if (substr($x['iso'], 0, 2) == 'au') {
					$location->parent = $australia;
					$location->type = $locationTypeState;
				}
			} else {
				$parent = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId' => $x['continent']));
				$location->parent = $parent;
			}

			if ($x['domain']) $location->domain = $this->context->domainRepositoryAccessor->get()->findOneBy(array('domain' => $x['domain']));

			//debug($location->parent); return;
			//debug($location); return;

			$this->model->persist($location);
		}
		$this->model->flush();
	}

	// ----------------------------------------------------------
	// ------------- Regions
	// ----------------------------------------------------------
	private function importRegions() {
		$locationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBy(array('slug' => 'region'));
		if (!$locationType) {
			d('Vytvaram region locationType');
			$locationType = $this->context->locationTypeEntityFactory->create();
			$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'region', 'en');
			$locationType->slug = 'region';
			$this->model->persist($locationType);			
		}

		$countryLocationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBy(array('slug' => 'country'));

		if ($this->developmentMode == TRUE) {
			$r = q('select * from regions where country_id = 46 order by id');
		} else {
			$r = q('select * from regions order by id');
		}

		while($x = mysql_fetch_array($r)) {
			$location = $this->context->locationEntityFactory->create();				

			$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew();
			$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
			$namePhrase->type = $this->dictionaryTypeName;
			$namePhrase->ready = TRUE;

			$r1 = q('select * from regions_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array();
				$variations[0][0] = array(
					'locative' => $x1['name_locative'],
				);
				$languageTemp = $this->context->languageRepositoryAccessor->get()->findOneBy(array('oldId' => $x1['language_id']));
				$t = $namePhraseService->createTranslation($languageTemp, $x['name']);
				$t->updateVariations($variations);
			}

			$location->name = $namePhrase;
			$location->type = $locationType;
			$location->slug = $x['name_url'];

			$location->oldId = $x['id'];

			$t = unserialize($x['polygons']);
			$location->polygons = $t;

			$location->parent = $this->context->locationRepositoryAccessor->get()->findOneBy(array(
				'oldId' => $x['country_id'], 
				'type' => $countryLocationType
			));

			//debug($location); exit;
			$this->model->persist($location);
		}
		$this->model->flush();
	}

	// ----------------------------------------------------------
	// ------------- Localities
	// ----------------------------------------------------------
	private function importLocalities() {

		$locationType = $this->context->locationTypeEntityFactory->create();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'locality', 'en');
		$locationType->slug = 'locality';
		
		$this->model->persist($locationType);

		$countryLocationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBy(array('slug' => 'country'));

		if ($this->developmentMode == TRUE) {
			$r = q('select * from localities where country_id = 46 order by id');
		} else {
			$r = q('select * from localities order by id');
		}
		while($x = mysql_fetch_array($r)) {
			$location = $this->context->locationEntityFactory->create();

			$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew();
			$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
			$namePhrase->type = $this->dictionaryTypeName;
			$namePhrase->ready = TRUE;

			$countryLocation = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$countryLocationType));
			$r1 = q('select * from localities_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array();
				$variations[0][0] = array(
					'locative' => $x1['name_locative'],
				);
				$languageTemp = $this->context->languageRepositoryAccessor->get()->findOneBy(array('oldId' => $x1['language_id']));
				$t = $namePhraseService->createTranslation($languageTemp, $x['name']);
				$t->updateVariations($variations);
			}

			$location->name = $namePhrase;
			$location->nameOfficial = $namePhrase;
			$location->nameShort = $namePhrase;
			$location->type = $locationType;
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parent = $countryLocation;

			//d($location); return;
			$this->model->persist($location);
		}
		$this->model->flush();
	}

}