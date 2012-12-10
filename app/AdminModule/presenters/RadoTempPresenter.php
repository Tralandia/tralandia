<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Nette\Utils\Arrays,
	Extras\Import as I,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

class RadoTempPresenter extends BasePresenter {

	public function actionGps() {
		$testValues = array(
			array('40:26:46S', '79:56:55E'),
			array('40:26:46.302S', '79:56:55.903E'),
			array('40°26′47″S', '79°58′36″E'),
			array('40d 26′ 47″ S', '79d 58′ 36″ E'),
			array('40.446195S', '79.948862E'),
			array('-40.446195', '79.948862'),
			array('-40° 26.7717', '79° 56.93172'),
		);

		$i = 0;
		foreach ($testValues as $key => $value) {
			$lat = new \Extras\Types\Latlong($value[0], 'latitude');
			$long = new \Extras\Types\Latlong($value[1], 'longitude');
			d($i, $value, (string)$lat, (string)$long);			
			$i++;
		}
	}

	public function actionAddress() {
		$testValues = array(
			array('40:26:46S', '79:56:55E'),
			array('40:26:46.302S', '79:56:55.903E'),
			array('40°26′47″S', '79°58′36″E'),
			array('40d 26′ 47″ S', '79d 58′ 36″ E'),
			array('40.446195S', '79.948862E'),
			array('-40.446195', '79.948862'),
			array('-40° 26.7717', '79° 56.93172'),
		);

		$lat = new \Extras\Types\Latlong($testValues[0][0], 'latitude');
		$long = new \Extras\Types\Latlong($testValues[0][1], 'longitude');
		$a = new \Entity\Contact\Address();
		$a->latitude = $lat;
		$a->longitude = $long;

		$aa = new Service\Contact\AddressNormalizer($a);
		$aa->updateFromLocation($lat, $long);
	}

	public function actionGoogleMapsApi() {
		$service = new \GoogleGeocodeServiceV3( new \CurlCommunicator() );

		// $response = $service->geocode('Springfield');

		// // Make sure we have a good result
		// if ( $response->isValid() && $response->hasResults() ) {
		//   d($response->getFormattedAddress());
		// } else {
		//   d('Invalid Response');
		// }

		// while ($response->valid()) {
		// 	// Get the State name
		// 	//d($response->getAddressComponentName(\GoogleGeocodeResponseV3::ACT_LOCALITY));
		// 	d($response->getFormattedAddress());
		// 	$response->next();
		// }

		$response = $service->reverseGeocode( 34.1346702, -118.4389877 );

		while ( $response->valid() ) {
			// Address component type we're checking for
			$component = \GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1;

			// Is it a city-level result?
			if ( $response->assertType( $component ) ) {
				// Get the city name
				d($response->getAddressComponentName( $component ));
				break;
			}
			$response->next();
		}

		// Show that the API assumes "Portland, OR" for "Portland USA"
		d($service->geocode( 'Portland USA' )->getFormattedAddress());

		// Now geocode the state of Maine and bias results to its viewport
		$maine = $service->geocode( 'Maine, USA' );
		$service->biasViewportByBoundsObject( $maine->getViewport() );

		// Re-geocode "Portland USA"
		d($service->geocode( 'Portland USA' )->getFormattedAddress());


		// Establish an ambiguous location
		$location = 'Toledo';

		// Bias for the USA
		$service->biasRegion( 'com' );
		d($service->geocode( $location )->getFormattedAddress());

		// Bias for Spain
		$service->biasRegion( 'es' );
		d($service->geocode( $location )->getFormattedAddress());
	}

	public function actionGoogleReverseGeocode() {
		$service = new \GoogleGeocodeServiceV3( new \CurlCommunicator(), NULL, array('language' => 'pl') );
		$response = $service->reverseGeocode(51.053205, 21.988672);
		while ( $response->valid() ) {
			// Address component type we're checking for
			$components = array(
				\GoogleGeocodeResponseV3::ACT_LOCALITY,
				\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_1,
				\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_2,
				\GoogleGeocodeResponseV3::ACT_ADMINISTRATIVE_AREA_LEVEL_3,
			);

			foreach ($components as $key => $value) {
				d($value, $response->getAddressComponentName( $value ));
			}

			$response->next();
		}
	}


}