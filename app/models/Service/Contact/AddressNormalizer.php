<?php

namespace Service\Contact;

use Nette\Utils\Arrays;

class AddressNormalizer extends \Nette\Object {

	protected $address;
	protected $geocodeService;

	public function __construct(\Entity\Contact\Address $address, \GoogleGeocodeServiceV3 $googleGeocodeService) {
		$this->address = $address;
		if (!$this->address->primaryLocation) {
			throw new \Exception("\Entity\Contact\Address has no primaryLocation", 1);
		}

		$this->geocodeService = $googleGeocodeService;
		$this->geocodeService->setRequestDefaults(array(
			'location' => $this->address->primaryLocation->iso,
			'language' => $this->address->primaryLocation->defaultLanguage->iso,
		));
	}

	public function updateFromLocation(\Extras\Types\Latlong $latitude, \Extras\Types\Latlong $longitude) {

		$response = $this->geocodeService->reverseGeocode($latitude->toFloat(), $longitude->toFloat());
		d($response);

		while ( $response->valid() ) {
			$component = \GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1;

			// Is it a city-level result?
			if ( $response->assertType( $component ) ) {
				// Get the city name
				d($response->getAddressComponentName( $component ));
				break;
			}
			$response->next();
		}		
	}
}