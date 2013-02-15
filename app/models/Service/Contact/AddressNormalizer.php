<?php

namespace Service\Contact;

use Nette\Utils\Arrays;
use Nette\Utils\Strings;

class AddressNormalizer extends \Nette\Object {

	const ADDRESS = 'address';
	const LOCALITY = 'locality';
	const SUBLOCALITY = 'subLocality';
	const POSTAL_CODE = 'postalCode';
	const PRIMARY_LOCATION = 'location';
	const LATITUDE = 'latitude';
	const LONGITUDE = 'longitude';

	protected $address;
	protected $geocodeService;

	protected $locationRepositoryAccessor;
	protected $locationTypeRepositoryAccessor;
	protected $locationDecoratorFactory;

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

	/**
	 * @param \GoogleGeocodeServiceV3 $googleGeocodeService
	 */
	public function __construct(\GoogleGeocodeServiceV3 $googleGeocodeService) {
		$this->geocodeService = $googleGeocodeService;
	}

	/**
	 * @param \Entity\Contact\Address $address
	 * @param bool $override
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function update(\Entity\Contact\Address $address, $override = FALSE) {
		$this->address = $address;
		if (!$this->address->primaryLocation) {
			throw new \Exception('\Entity\Contact\Address has no primaryLocation');
		}

		$this->geocodeService->setRequestDefaults(array(
			'region' => $this->address->primaryLocation->iso,
			'language' => $this->address->primaryLocation->defaultLanguage->iso,
		));

		$latLong = $this->address->getGps();
		if ($latLong->isValid()) {
			$info = $this->getInfoUsingGps($latLong);
		} else {
			$info = $this->getInfoUsingAddress(
				$this->address->primaryLocation, 
				$this->address->address, 
				$this->address->subLocality, 
				$this->address->locality->name->getTranslationText($this->address->primaryLocation->defaultLanguage, TRUE),
				$this->address->postalCode
			);
		}

		$this->updateAddressData($info, TRUE);

		return $this->address->status;
	}

	/**
	 * @param \Extras\Types\Latlong $latLong
	 *
	 * @return array|bool
	 */
	public function getInfoUsingGps(\Extras\Types\Latlong $latLong) {
		$latitude = $latLong->getLatitude();
		$longitude = $latLong->getLongitude();

		$response = $this->geocodeService->reverseGeocode($latitude, $longitude);
		
		if (!$response->hasResults() || !$response->isValid()) {
			return FALSE;
		}

		return $this->parseResponse($response);

	}

	/**
	 * @param \Entity\Location\Location $primaryLocation
	 * @param string $address
	 * @param string $subLocality
	 * @param string $locality
	 * @param string $postalCode
	 *
	 * @return array|bool
	 */
	public function getInfoUsingAddress(\Entity\Location\Location $primaryLocation, $address = '', $subLocality = '', $locality = '', $postalCode = '') {

		$formattedAddress = implode(', ', array_filter(array(
			$address, $subLocality, $locality, $postalCode,
			$primaryLocation->name->getTranslationText($primaryLocation->defaultLanguage, TRUE)
		)));

		$response = $this->geocodeService->geocode($formattedAddress);
		if (!$response->hasResults() || !$response->isValid()) {
			return FALSE;
		}
		
		return $this->parseResponse($response);
	}

	/**
	 * @param \GoogleGeocodeResponseV3 $response
	 *
	 * @return array
	 */
	private function parseResponse(\GoogleGeocodeResponseV3 $response) {
		$info = array();
		$components = array(
			\GoogleGeocodeResponseV3::ACT_ROUTE => NULL,
			\GoogleGeocodeResponseV3::ACT_STREET_NUMBER => NULL,
			\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1 => NULL,
			\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_2 => NULL,
			\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_3 => NULL,
			\GoogleGeocodeResponseV3::ACT_LOCALITY => self::LOCALITY,
			\GoogleGeocodeResponseV3::ACT_SUBLOCALITY => self::SUBLOCALITY,
			\GoogleGeocodeResponseV3::ACT_POSTAL_CODE => self::POSTAL_CODE,
			\GoogleGeocodeResponseV3::ACT_COUNTRY => self::PRIMARY_LOCATION,
		);

		$lowerCased = array(
			//\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1,
			\GoogleGeocodeResponseV3::ACT_COUNTRY,
		);
		while ( $response->valid() ) {
			foreach ($components as $key => $value) {
				$infoKey = $value ?: $key;
				if (!isset($info[$infoKey])) {
					$t = $response->getAddressComponentName($key, \GoogleGeocodeResponseV3::NAME_SHORT);
					if ($t !== NULL) {
						if (in_array($key, $lowerCased)) {
							$t = Strings::lower($t);
						}
						$info[$infoKey] = $t;
					}
	 			}
			}
			$response->next();
		}

		// If we don't have LOCALITY, try to use ADMINISTRATIVE LEVEL 3
		if (!isset($info[self::LOCALITY]) && isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_3])) {
			$info[self::LOCALITY] = $info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_3];
		}

		// If we still don't have LOCALITY, try to use ADMINISTRATIVE LEVEL 2
		if (!isset($info[self::LOCALITY]) && isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_2])) {
			$info[self::LOCALITY] = $info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_2];
		}

		// If we still don't have LOCALITY, try to use ADMINISTRATIVE LEVEL 1
		if (!isset($info[self::LOCALITY]) && isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1])) {
			$info[self::LOCALITY] = $info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1];
		}

		// IF it's in USA, create a 4-letter ISO code
		if (isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1]) && isset($info[self::PRIMARY_LOCATION]) && Strings::lower($info[self::PRIMARY_LOCATION]) == 'us') {
			$info[self::PRIMARY_LOCATION] = $info[self::PRIMARY_LOCATION] . $info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1];
		}

		$info[self::PRIMARY_LOCATION] = $this->locationRepositoryAccessor->get()->findOneByIso($info[self::PRIMARY_LOCATION]);

		if (isset($info[self::LOCALITY]) && isset($info[self::SUBLOCALITY])) {
			if ($info[self::LOCALITY] == $info[self::SUBLOCALITY]) {
				unset($info[self::SUBLOCALITY]);
			}
		}

		$l = $response->getLocation();
		if (isset($l->lat) && isset($l->lng)) {
			$info[self::LATITUDE] = $l->lat;
			$info[self::LONGITUDE] = $l->lng;
		}

		$address = [
			Arrays::get($info, \GoogleGeocodeResponseV3::ACT_ROUTE, NULL),
			Arrays::get($info, \GoogleGeocodeResponseV3::ACT_STREET_NUMBER, NULL),
		];

		$info[self::ADDRESS] = implode(' ', array_filter($address));

		return $info;
	}

	/**
	 * @param array $info
	 * @param $override
	 */
	protected function updateAddressData(array $info, $override) {
		// If the location is outside the primaryLocation, return false
		if ($info[self::PRIMARY_LOCATION] instanceof \Entity\Location\Location
			&& $info[self::PRIMARY_LOCATION]->getId() != $this->address->primaryLocation->getId())
		{
			$this->address->status = \Entity\Contact\Address::STATUS_MISPLACED;
		} else if (isset($info[self::LOCALITY])) {
			$this->address->status = \Entity\Contact\Address::STATUS_OK;
		} else {
			$this->address->status = \Entity\Contact\Address::STATUS_INCOMPLETE;
		}

		// Set the Address Entity details

		// Latitude / Longitude
		$latlong = new \Extras\Types\Latlong($info[self::LATITUDE], $info[self::LONGITUDE]);
		$this->address->setGps($latlong);

		// Address
		if (!$this->address->address || $override === TRUE) {
			$this->address->address = Arrays::get($info, self::ADDRESS, NULL);
		}

		// Sub locality
		if (!$this->address->subLocality || $override === TRUE) {
			$this->address->subLocality = Arrays::get($info, self::SUBLOCALITY, NULL);
		}

		// Postal Code
		$this->address->postalCode = Arrays::get($info, self::POSTAL_CODE, NULL);

		// Locality
		$locality = Arrays::get($info, self::LOCALITY, NULL);
		$this->setLocality($locality);
	}

	/**
	 * @param $locality
	 *
	 * @throws \Exception
	 */
	protected function setLocality($locality) {
		if ($locality === NULL) {
			$this->address->locality = NULL;
		} else if ($locality instanceof \Entity\Location\Location) {
			if ($locality->parent != $this->address->primaryLocation) {
				throw new \Exception("AddressNormalizer - can't set the locality,
				because it is outside the primaryLocation", 1);
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

				$namePhrase->createTranslation($this->address->primaryLocation->defaultLanguage, $locality);

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