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
	Service\Log as SLog;

class ImportLocations extends BaseImport {

	private $dictionaryTypeName;
	private $continentsByOldId = array();

	public function doImport($subsection) {
		

		$this->dictionaryTypeName = $this->createPhraseType('\Location\Location', 'name', 'NATIVE');

		$this->{$subsection}();

	}

	// ----------------------------------------------------------
	// ------------- CONTINENTS
	// ----------------------------------------------------------
	private function importContinents() {
		$language = $this->context->languageRepositoryAccessor->get()->findOneBy(array('iso' => 'en'));
		$en = $language;

		// Create location type for world
		$locationType = $this->context->locationTypeEntityFactory->create();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'world', $language);
		$locationType->slug = 'world';
		$locationType->primary = TRUE;
		
		$this->model->persist($locationType);
		$this->model->flush();

		$worldType = $locationType;

		$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
		$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhrase->sourceLanguage = $en;
		$namePhraseService->createTranslation($language, 'World');
		$this->model->persist($namePhrase);
		$this->model->flush();

		// Create location World
		$s = $this->context->locationEntityFactory->create();
		$s->name = $namePhrase;
		$s->iso = 'com';
		$s->type = $worldType;
		$s->slug = 'world';
		$s->localName = 'world';
		$s->domain = $this->context->domainRepositoryAccessor->get()->findOneBy(array('domain' => 'tralandia.com'));
		$s->defaultLanguage = $this->context->languageRepositoryAccessor->get()->findOneByIso('en');
		$this->model->persist($s);
		$this->model->flush();

		$world = $s;

		$locationType = $this->context->locationTypeEntityFactory->create();
		$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'continent', $language);
		$locationType->slug = 'continent';
		$locationType->primary = TRUE;
		$this->model->persist($locationType);
		$this->model->flush();

		$r = q('select * from continents where id not in (4, 5, 10) order by id');
		while($x = mysql_fetch_array($r)) {
			$s = $this->context->locationEntityFactory->create();
			$s->name = $this->createNewPhrase($this->dictionaryTypeName, $x['name_dic_id']);
			$s->name->sourceLanguage = $en;
			$s->type = $locationType;
			$s->localName = qc('select text from z_en where id = '.$x['name_dic_id']);
			$s->slug = $s->localName;
			$s->parent = $world;
			$s->oldId = $x['id'];

			$this->model->persist($s);
			//debug($s);
		}
		$this->model->flush();

		// Create USA
		$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
		$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhraseService->createTranslation($language, 'USA');
		$this->model->persist($namePhrase);

		$s = $this->context->locationEntityFactory->create();
		$s->name = $namePhrase;
		$s->name->sourceLanguage = $en;
		$s->type = $locationType;
		$s->slug = 'usa';
		$s->localName = 'Usa';
		$s->iso = 'us';
		$s->parent = $world;
		$s->defaultLanguage = $language;
		$this->model->persist($s);

		$usa = $s;

		// Create Canada
		$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
		$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhraseService->createTranslation($language, 'Canada');
		$this->model->persist($namePhrase);

		$s = $this->context->locationEntityFactory->create();
		$s->name = $namePhrase;
		$s->name->sourceLanguage = $en;
		$s->type = $locationType;
		$s->slug = 'canada';
		$s->localName = 'Canada';
		$s->iso = 'ca';
		$s->parent = $world;
		$s->defaultLanguage = $language;
		$this->model->persist($s);

		$canada = $s;

		// Create Australia
		$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
		$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
		$namePhrase->type = $this->dictionaryTypeName;
		$namePhraseService->createTranslation($language, 'Australia');
		$this->model->persist($namePhrase);

		$s = $this->context->locationEntityFactory->create();
		$s->name = $namePhrase;
		$s->name->sourceLanguage = $en;
		$s->type = $locationType;
		$s->slug = 'australia';
		$s->localName = 'Australia';
		$s->iso = 'au';
		$s->parent = $world;
		$s->defaultLanguage = $language;
		$this->model->persist($s);

		$australia = $s;

		$this->model->flush();

		// Countries

		$dictionaryType = $this->createPhraseType('\Location\Type', 'name', 'ACTIVE');

		$locationTypeCountry = $this->context->locationTypeEntityFactory->create();
		$locationTypeCountry->name = $this->createNewPhrase($dictionaryType, 865);
		$locationTypeCountry->slug = 'country';
		$this->model->persist($locationTypeCountry);

		$r = q('select * from countries order by id');
		//$r = q('select * from countries where id = 46 order by id');
		while($x = mysql_fetch_array($r)) {
			$location = $this->context->locationEntityFactory->create();

			$location->oldId = $x['id'];
			$location->iso = $x['iso'];
			$location->iso3 = $x['iso3'];

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
				$variations[0][0] = array_filter($variations[0][0]);
				$t->updateVariations($variations);
			}

			$missingNames = array(
				'tk' => 'Tokelau',
				'tf' => 'French Southern Territories',
				'kp' => 'Democratic People\'s Republic of Korea',
				'hm' => 'Heard Island and McDonald Islands',
				'gs' => 'South Georgia and the South Sandwich Islands',
				'bv' => 'Bouvet Island',
			);

			if (isset($missingNames[$location->iso])) {
				$namePhrase->setTranslationText($en, $missingNames[$location->iso]);
			}

			$location->name = $namePhrase;

			$location->type = $locationTypeCountry;
			$languageTemp = $this->context->languageRepositoryAccessor->get()->findOneBy(array('iso' => 'en'));
			$location->localName = $namePhraseService->getTranslation($languageTemp)->translation;
			$location->slug = $location->localName;
			
			$location->polygons = NULL;
			if ($x['latitude'] != 0 && $x['longitude'] != 0) {
				$gps = new \Extras\Types\Latlong($x['latitude'], $x['longitude']);
				$location->setGps($gps);
			}
			$location->phonePrefix = $x['phone_prefix'];
			$location->defaultZoom = $x['default_zoom'];

			if (strlen($x['iso']) == 4) {
				if (substr($x['iso'], 0, 2) == 'us') {
					$location->parent = $usa;
				} else if (substr($x['iso'], 0, 2) == 'ca') {
					$location->parent = $canada;
				} else if (substr($x['iso'], 0, 2) == 'au') {
					$location->parent = $australia;
				}
			} else if ($x['iso'] == 'pm') {
				$location->parent = $canada;
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
			//d('Vytvaram region locationType');
			$locationType = $this->context->locationTypeEntityFactory->create();
			$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'region', 'en');
			$locationType->slug = 'region';
			$this->model->persist($locationType);			
		}

		$countryLocationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBy(array('slug' => 'country'));

		$countryId = qc('select id from countries where iso = "'.$this->presenter->getParameter('countryIso').'"');
		$r = q('select * from regions where country_id = '.$countryId.' order by id');

		//d(mysql_num_rows($r)); exit;

		while($x = mysql_fetch_array($r)) {
			$location = $this->context->locationEntityFactory->create();				

			$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
			$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
			$namePhrase->type = $this->dictionaryTypeName;

			$r1 = q('select * from regions_translations where location_id = '.$x['id']);
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array();
				$variations[0][0] = array(
					'nominative' => $x1['name'] ? $x1['name'] : $x['name'],
					'locative' => $x1['name_locative'],
				);
				$languageTemp = $this->context->languageRepositoryAccessor->get()->findOneBy(array('oldId' => $x1['language_id']));
				$t = $namePhraseService->createTranslation($languageTemp, $x['name']);
				$variations[0][0] = array_filter($variations[0][0]);
				$t->updateVariations($variations);
			}
			//d($namePhrase); exit;
			$location->name = $namePhrase;
			$location->type = $locationType;
			$location->localName = $x['name'];
			$location->slug = $x['name_url'];

			$location->oldId = $x['id'];

			$t = unserialize($x['polygons']);
			$location->polygons = $t;

			$location->parent = $this->context->locationRepositoryAccessor->get()->findOneBy(array(
				'oldId' => $x['country_id'], 
				'type' => $countryLocationType
			));

			d($x['country_id']);
			$namePhrase->sourceLanguage = $location->parent->defaultLanguage;


			//debug($location); exit;
			$this->model->persist($location);
		}
		$this->model->flush();
	}

	// ----------------------------------------------------------
	// ------------- Localities
	// ----------------------------------------------------------
	private function importLocalities() {

		$locationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBy(array('slug' => 'locality'));

		if (!$locationType) {
			$locationType = $this->context->locationTypeEntityFactory->create();
			$locationType->name = $this->createPhraseFromString('\Location\Location', 'name', 'NATIVE', 'locality', 'en');
			$locationType->slug = 'locality';
		}
		
		$this->model->persist($locationType);

		$countryLocationType = $this->context->locationTypeRepositoryAccessor->get()->findOneBy(array('slug' => 'country'));

		$countryId = qc('select id from countries where iso = "'.$this->presenter->getParameter('countryIso').'"');
		$r = q('select * from localities where country_id = '.$countryId.' order by id');

		while($x = mysql_fetch_array($r)) {
			$x['name'] = Strings::firstUpper($x['name']);

			$location = $this->context->locationEntityFactory->create();

			$namePhrase = $this->context->phraseRepositoryAccessor->get()->createNew(FALSE);
			$namePhraseService = $this->context->phraseDecoratorFactory->create($namePhrase);
			$namePhrase->type = $this->dictionaryTypeName;

			$countryLocation = $this->context->locationRepositoryAccessor->get()->findOneBy(array('oldId'=>$x['country_id'], 'type'=>$countryLocationType));

			$namePhrase->sourceLanguage = $countryLocation->defaultLanguage;

			$r1 = q('select * from localities_translations where location_id = '.$x['id']);
			$translationsCount = 0;
			while ($x1 = mysql_fetch_array($r1)) {
				$variations = array();
				$variations[0][0] = array(
					'nominative' => $x1['name'] ? $x1['name'] : $x['name'],
					'locative' => $x1['name_locative'],
				);
				$languageTemp = $this->context->languageRepositoryAccessor->get()->findOneBy(array('oldId' => $x1['language_id']));
				$t = $namePhraseService->createTranslation($languageTemp, $x['name']);
				$variations[0][0] = array_filter($variations[0][0]);
				$t->updateVariations($variations);
				$translationsCount++;
			}

			if ($namePhraseService->getTranslation($countryLocation->defaultLanguage) == FALSE) {
				$languageTemp = $countryLocation->defaultLanguage;
				$t = $namePhraseService->createTranslation($languageTemp, $x['name']);
			}

			$location->name = $namePhrase;
			$location->type = $locationType;
			$location->localName = $x['name'];			
			$location->slug = $x['name_url'];			

			$location->oldId = $x['id'];

			$location->parent = $countryLocation;

			$this->model->persist($location);
		}
		$this->model->flush();
	}

}