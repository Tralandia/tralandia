<?php

namespace Service\Contact;

use Nette\Utils\Arrays;

class AddressNormalizer extends \Nette\Object {

	protected $address;
	protected $geocodeService;

	public function __construct(\Entity\Contact\Address $address, \GoogleGeocodeServiceV3 $googleGeocodeService) {
		$this->address = $address;

		$this->geocodeService = $googleGeocodeService;
	}

	public function updateFromLocation($latitude, $longitude) {

		$latitude = new Extras\Types\Latlong($latitude);
		$longitude = new Extras\Types\Latlong($longitude);

		if (!$latitude || !$longitude) {
			return FALSE;
		}

		$response = $this->geocodeService->reverseGeocode($latitude, $longitude);

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