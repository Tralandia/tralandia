<?php

namespace Service\Contact;

use Entity\Contact\Address;
use Entity\Language;
use Environment\Environment;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;
use Tralandia\Location\Locations;

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

	/**
	 * @var \Environment\Environment
	 */
	private $environment;

	/**
	 * @var \Tralandia\Location\Locations
	 */
	private $locations;


	/**
	 * @param \GoogleGeocodeServiceV3 $googleGeocodeService
	 * @param \Environment\Environment $environment
	 * @param \Tralandia\Location\Locations $locations
	 */
	public function __construct(\GoogleGeocodeServiceV3 $googleGeocodeService, Environment $environment, Locations $locations) {
		$this->geocodeService = $googleGeocodeService;
		$this->environment = $environment;
		$this->geocodeService->setRequestDefaults(array(
			'region' => $environment->getPrimaryLocation()->getIso(),
			'language' => $environment->getPrimaryLocation()->getDefaultLanguage()->getIso(),
		));
		$this->locations = $locations;
	}


	/**
	 * @param \Entity\Contact\Address $address
	 * @param bool $override
	 * @param \Entity\Language $language
	 *
	 * @return string
	 */
	public function update(\Entity\Contact\Address $address, $override = FALSE,Language $language = NULL) {
		$options = [];
		if ($address->primaryLocation) {
			$options['region'] = $address->getPrimaryLocation()->getIso();
			$options['language'] = $address->getPrimaryLocation()->getDefaultLanguage()->getIso();
		}

		if($language) {
			$options['language'] = $language->getIso();
		}

		if(count($options)) $this->geocodeService->setRequestDefaults($options);

		$latLong = $address->getGps();
		if ($latLong->isValid()) {
			$info = $this->getInfoUsingGps($latLong);
		} else {
			$info = $this->getInfoUsingAddress($address);
		}

		$this->updateAddressData($address, $info, TRUE);

		return $address->status;
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
	 * @param \Entity\Contact\Address|string $address
	 *
	 * @return array|bool
	 */
	public function getInfoUsingAddress($address) {

		if($address instanceof Address) {
			$address = $this->formatAddress($address);
		}

		$response = $this->geocodeService->geocode($address);
		if (!$response->hasResults() || !$response->isValid()) {
			return FALSE;
		}

		return $this->parseResponse($response);
	}


	/**
	 * @param \Entity\Contact\Address $address
	 *
	 * @return string
	 */
	private function formatAddress(Address $address)
	{
		$primaryLocation = $address->getPrimaryLocation();
		$formattedAddress = implode(', ', array_filter(array(
			$address->getAddress(),
			$address->getSubLocality(),
			$address->getLocality()->getName()->getTranslationText($address->getPrimaryLocation()->getDefaultLanguage(), TRUE),
			$address->getPostalCode(),
			$primaryLocation->getName()->getTranslationText($primaryLocation->getDefaultLanguage(), TRUE)
		)));

		return $formattedAddress;
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
		if ($this->itsInUsa($info) || $this->itsInCanada($info) || $this->itsInAustralia($info)) {
			$info[self::PRIMARY_LOCATION] = $info[self::PRIMARY_LOCATION] . $info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1];
		}

		if (isset($info[self::LOCALITY]) && isset($info[self::SUBLOCALITY])) {
			if ($info[self::LOCALITY] == $info[self::SUBLOCALITY]) {
				unset($info[self::SUBLOCALITY]);
			}
		}

		$info[self::PRIMARY_LOCATION] = $this->locations->findOneByIso($info[self::PRIMARY_LOCATION]);
		if(isset($info[self::LOCALITY])) {
			$info[self::LOCALITY] = $this->locations->findOrCreateLocality($info[self::LOCALITY], $info[self::PRIMARY_LOCATION]);
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

		// nepovinne polia, ak neexistuju tak ich nastavim na NULL
		$info[self::POSTAL_CODE] = Arrays::get($info, AddressNormalizer::POSTAL_CODE, NULL);
		$info[self::SUBLOCALITY] = Arrays::get($info, AddressNormalizer::SUBLOCALITY, NULL);

		if(!$this->isInfoValid($info)) {
			return NULL;
		}

		return $info;
	}

	protected function isInfoValid(array $info)
	{
		return isset($info[self::PRIMARY_LOCATION], $info[self::LOCALITY], $info[self::LATITUDE], $info[self::LONGITUDE]);
	}

	/**
	 * @param $address
	 * @param array $info
	 * @param $override
	 */
	protected function updateAddressData($address, array $info, $override) {
		// If the location is outside the primaryLocation, return false
		if ($info[self::PRIMARY_LOCATION] instanceof \Entity\Location\Location) {
			$address->primaryLocation = $info[self::PRIMARY_LOCATION];
		}

		// Set the Address Entity details

		// Latitude / Longitude
		$latlong = new \Extras\Types\Latlong($info[self::LATITUDE], $info[self::LONGITUDE]);
		$address->setGps($latlong);

		// Address
		if (!$address->address || $override === TRUE) {
			$address->address = Arrays::get($info, self::ADDRESS, NULL);
		}

		// Sub locality
		if (!$address->subLocality || $override === TRUE) {
			$address->subLocality = Arrays::get($info, self::SUBLOCALITY, NULL);
		}

		// Postal Code
		$address->postalCode = Arrays::get($info, self::POSTAL_CODE, NULL);

		// Locality
		$locality = Arrays::get($info, self::LOCALITY, NULL);
		try {
			$this->setLocality($address, $locality);
		} catch (WrongCountryException $e) {
		}

	}

	/**
	 * @param \Entity\Contact\Address $address
	 * @param $locality
	 *
	 * @throws WrongCountryException
	 */
	protected function setLocality(Address $address, $locality) {
		if ($locality === NULL) {
			$address->locality = NULL;
		} else if ($locality instanceof \Entity\Location\Location) {
			if ($locality->parent->getId() != $address->primaryLocation->getId()) {
				throw new WrongCountryException("AddressNormalizer - can't set the locality,
				because it is outside the primaryLocation", 1);
			} else {
				$address->locality = $locality;
			}
		} else {
			$locality = $this->locations->findOrCreateLocality($locality, $address->getPrimaryLocation());

			$address->locality = $locality;
		}
	}

	/**
	 * @param $info
	 *
	 * @return bool
	 */
	private function itsInUsa($info)
	{
		return isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1])
			&& isset($info[self::PRIMARY_LOCATION])
			&& Strings::lower($info[self::PRIMARY_LOCATION]) == 'us';
	}

	private function itsInCanada($info)
	{
		return isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1])
			&& isset($info[self::PRIMARY_LOCATION])
			&& Strings::lower($info[self::PRIMARY_LOCATION]) == 'ca';
	}

	private function itsInAustralia($info)
	{
		return isset($info[\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1])
			&& isset($info[self::PRIMARY_LOCATION])
			&& Strings::lower($info[self::PRIMARY_LOCATION]) == 'au';
	}

}

class AddressNormalizerException extends \Exception {}
class WrongCountryException extends AddressNormalizerException {}
class WrongAddressInfoException extends AddressNormalizerException {}
