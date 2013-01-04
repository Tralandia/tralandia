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

	protected $languageRepositoryAccessor;
	protected $locationRepositoryAccessor;
	protected $rentalTypeRepositoryAccessor;
	protected $rentalRepositoryAccessor;
	protected $rentalDecoratorFactory;

	protected $addressNormalizerFactory;

	protected $polygonService;

	/**
	 * @autowire
	 * @var Service\Statistics\RentalRegistrations
	 */
	protected $rentalRegistrationsStats;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function injectAddressNormalizer(\Service\Contact\IAddressNormalizerFactory $factory) {
		$this->addressNormalizerFactory = $factory;
	}

	public function inject(\Service\PolygonService $service, \Model\Rental\IRentalDecoratorFactory $rentalDecoratorFactory) {
		$this->polygonService = $service;
		$this->rentalDecoratorFactory = $rentalDecoratorFactory;
	}

	public function actionTest() {
		$results = $this->locationRepositoryAccessor->get()->getRentalCounts();
		d($results);
	}

	public function actionGps() {
		$testValues = array(
			array('40:26:46S', '79:56:55E'),
			array('40:26:46.302S', '79:56:55.903E'),
			array('40°26′46″S', '79°56′55″E'),
			array('40d 26′ 46″ S', '79d 56′ 55″ E'),
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
			array('45.470725', '-98.47566'),
			array('47.978267', '18.154208'),
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
		$a->primaryLocation = $this->locationRepositoryAccessor->get()->findOneByIso('ussd');
		//$a->latitude = $lat;
		//$a->longitude = $long;

		$aa = $this->addressNormalizerFactory->create($a);
		//$aa->updateUsingGPS($lat, $long, TRUE);
		$aa->updateUsingAddress('800-1018 E 61st St, Sioux Falls, South Dakota 57108');
	}

	public function actionPolygons() {
		$testValues = array(
			array('45.470725', '-98.47566'),
		);

		$lat = new \Extras\Types\Latlong($testValues[0][0], 'latitude');
		$long = new \Extras\Types\Latlong($testValues[0][1], 'longitude');

		$rental = $this->rentalRepositoryAccessor->get()->find(1);
		$normalizer = $this->addressNormalizerFactory->create($rental->address);
		$normalizer->updateUsingAddress('Demänovská Dolina 584, Slovensko');
		$this->polygonService->setLocationsForRental($rental);


		// $location = $this->locationRepositoryAccessor->get()->find(335);
		// $this->polygonService->setRentalsForLocation($location);
		//d($rental->address);

		$this->rentalRepositoryAccessor->get()->persist($rental);
		$this->rentalRepositoryAccessor->get()->flush();
	}

	public function actionRank() {
		$rental = $this->rentalRepositoryAccessor->get()->find(1);
		$rentalDecorator = $this->rentalDecoratorFactory->create($rental);
		$rentalDecorator->calculateRank();

		$this->rentalRepositoryAccessor->get()->persist($rental);
		$this->rentalRepositoryAccessor->get()->flush();
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

	public function actionStatsRegistrations() {
		d($this->rentalRegistrationsStats->getData());
	}


}