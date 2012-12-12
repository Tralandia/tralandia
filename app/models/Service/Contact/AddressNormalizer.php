<?php

namespace Service\Contact;

use Nette\Utils\Arrays;
use Nette\Utils\Strings;

class AddressNormalizer extends \Nette\Object {

	protected $address;
	protected $geocodeService;

	protected $locationRepositoryAccessor;
	protected $locationTypeRepositoryAccessor;
	protected $locationDecoratorFactory;
	protected $phraseDecoratorFactory;

	protected $phraseRepositoryAccessor;
	protected $phraseTypeRepositoryAccessor;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $dic->locationTypeRepositoryAccessor;
		$this->phraseRepositoryAccessor = $dic->phraseRepositoryAccessor;
		$this->phraseTypeRepositoryAccessor = $dic->phraseTypeRepositoryAccessor;
	}

	public function inject(\Model\Location\ILocationDecoratorFactory $factory) {
		$this->locationDecoratorFactory = $factory;
	}

	public function injectPhrase(\Model\Phrase\IPhraseDecoratorFactory $factory) {
		$this->phraseDecoratorFactory = $factory;
	}

	public function __construct(\Entity\Contact\Address $address, \GoogleGeocodeServiceV3 $googleGeocodeService) {
		$this->address = $address;
		if (!$this->address->primaryLocation) {
			throw new \Exception("\Entity\Contact\Address has no primaryLocation", 1);
		}

		$this->geocodeService = $googleGeocodeService;
		$this->geocodeService->setRequestDefaults(array(
			'region' => $this->address->primaryLocation->iso,
			'language' => $this->address->primaryLocation->defaultLanguage->iso,
		));
	}

	public function updateUsingGPS(\Extras\Types\Latlong $latitude, \Extras\Types\Latlong $longitude, $override = FALSE) {

		$response = $this->geocodeService->reverseGeocode($latitude->toFloat(), $longitude->toFloat());
		
		if (!$response->hasResults() || !$response->isValid()) {
			return FALSE;
		}

		return $this->updateAddressData($response, $override);
	}

	public function updateUsingAddress($address) {
		$response = $this->geocodeService->geocode($address);
		if (!$response->hasResults() || !$response->isValid()) {
			return FALSE;
		}
		
		return $this->updateAddressData($response, TRUE);
	}

	protected function updateAddressData($response, $override) {

		$info = array();
		$components = array(
			\GoogleGeocodeResponseV3::ACT_ROUTE,
			\GoogleGeocodeResponseV3::ACT_STREET_NUMBER,
			\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1,
			\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_2,
			\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_3,
			\GoogleGeocodeResponseV3::ACT_LOCALITY,
			\GoogleGeocodeResponseV3::ACT_SUBLOCALITY,
			\GoogleGeocodeResponseV3::ACT_POSTAL_CODE,
			\GoogleGeocodeResponseV3::ACT_COUNTRY,
		);

		$lowerCased = array(
			\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1,
			\GoogleGeocodeResponseV3::ACT_COUNTRY,
		);
		while ( $response->valid() ) {
			foreach ($components as $key => $value) {
				if (!isset($info[$value])) {
					$t = $response->getAddressComponentName($value, \GoogleGeocodeResponseV3::NAME_SHORT);
					if ($t !== NULL) {
						if (in_array($value, $lowerCased)) {
							$t = Strings::lower($t);
						}
						$info[$value] = $t;
					}
	 			}
			}
			$response->next();
		}

		// If we don't have LOCALITY, try to use ADMINISTRATIVE LEVEL 3
		if (!isset($info[\GoogleGeocodeResponseV3::ACT_LOCALITY]) && isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_3])) {
			$info[\GoogleGeocodeResponseV3::ACT_LOCALITY] = $info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_3];
		}

		// IF it's in USA, create a 4-letter ISO code
		if (isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1]) && isset($info[\GoogleGeocodeResponseV3::ACT_COUNTRY]) && Strings::lower($info[\GoogleGeocodeResponseV3::ACT_COUNTRY]) == 'us') {
			$info[\GoogleGeocodeResponseV3::ACT_COUNTRY] = $info[\GoogleGeocodeResponseV3::ACT_COUNTRY].$info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1];
		}
		d($info);

		// If the location is outside the primaryLocation, return false
		if (Strings::lower($info[\GoogleGeocodeResponseV3::ACT_COUNTRY]) != $this->address->primaryLocation->iso) {
			$this->address->status = \Entity\Contact\Address::STATUS_MISPLACED;
			return \Entity\Contact\Address::STATUS_MISPLACED;
		} else if (isset($info[\GoogleGeocodeResponseV3::ACT_LOCALITY])) {
			$this->address->status = \Entity\Contact\Address::STATUS_OK;
		} else {
			$this->address->status = \Entity\Contact\Address::STATUS_INCOMPLETE;
		}

		// Set the Address Entity details

		// Latitude / Longitude
		$this->address->latitude = new \Extras\Types\Latlong($response->getLocation()->lat);
		$this->address->longitude = new \Extras\Types\Latlong($response->getLocation()->lng);

		// Address
		if ((!$this->address->address || $override === TRUE) && isset($info[\GoogleGeocodeResponseV3::ACT_ROUTE])) {
			$t = $info[\GoogleGeocodeResponseV3::ACT_ROUTE].' ';
			if (isset($info[\GoogleGeocodeResponseV3::ACT_STREET_NUMBER])) {
				$t.=$info[\GoogleGeocodeResponseV3::ACT_STREET_NUMBER];
			}
			$t = trim($t);
			if (strlen($t) > 0) {
				$this->address->address = $t;
			} else {
				$this->address->address = NULL;
			}
		}

		// Sublocality
		if (!$this->address->subLocality || $override === TRUE) {
			if (isset($info[\GoogleGeocodeResponseV3::ACT_SUBLOCALITY])) {
				$this->address->subLocality = $info[\GoogleGeocodeResponseV3::ACT_SUBLOCALITY];
			} else {
				$this->address->subLocality = NULL;
			}
		}

		// Postal Code
		if (isset($info[\GoogleGeocodeResponseV3::ACT_POSTAL_CODE])) {
			$this->address->postalCode = $t;
		} else {
			$this->address->postalCode = NULL;
		}

		// Locality
		if (isset($info[\GoogleGeocodeResponseV3::ACT_LOCALITY])) {
			$this->setLocality($info[\GoogleGeocodeResponseV3::ACT_LOCALITY]);
		} else {
			$this->setLocality(NULL);
		}
	}

	protected function setLocality($locality) {
		if ($locality === NULL) {
			$this->address->locality = NULL;
		} else if ($locality instanceof \Entity\Locality\Locality) {
			if ($locality->parent != $this->address->primaryLocation) {
				throw new Exception("AddressNormalizer - can't set the locality, because it is outside the primaryLocation", 1);
			} else {
				$this->address->locality = $locality;
			}
		} else {
			$locationType = $this->locationTypeRepositoryAccessor->get()->findOneBySlug('locality');
			$webalizedName = Strings::webalize($locality);
			$existingLocality = $this->locationRepositoryAccessor->get()->findOneBy(array(
				'type' => $locationType,
				'parent' => $this->address->primaryLocation,
				'slug' => $webalizedName
			));

			if ($existingLocality) {
				$this->address->locality = $existingLocality;
			} else {
				$newLocality = $this->locationRepositoryAccessor->get()->createNew();
				$newLocalityDecorator = $this->locationDecoratorFactory->create($newLocality);

				$namePhrase = $this->phraseRepositoryAccessor->get()->createNew();
				$phraseType = $this->phraseTypeRepositoryAccessor->get()->findOneBy(array('entityName' => '\Entity\Location\Location'));

				$namePhrase->type = $phraseType;
				$namePhrase->sourceLanguage = $this->address->primaryLocation->defaultLanguage;

				$namePhraseDecorator = $this->phraseDecoratorFactory->create($namePhrase);
				$namePhraseDecorator->createTranslation($this->address->primaryLocation->defaultLanguage, $locality);

				$newLocality->parent = $this->address->primaryLocation;
				$newLocality->type = $locationType;

				// We must save the new location to be able to work on it's slug
				$this->locationRepositoryAccessor->get()->persist($newLocality);
				$this->locationRepositoryAccessor->get()->flush($newLocality);

				$newLocalityDecorator->setName($namePhrase);
				$this->address->locality = $newLocality;
				$this->locationRepositoryAccessor->get()->persist($newLocality);
				$this->locationRepositoryAccessor->get()->flush($newLocality);
			}
		}		
	}
}