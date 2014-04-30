<?php
namespace Tests\Rental;

use Nette, Extras;
use Service\Contact;

/**
 * @backupGlobals disabled
 * @author Radoslav Toth
 */
class AddressNormalizerTest extends \Tests\TestCase
{

	/**
	 * @var \Entity\Location\Location[]
	 */
	public $location = array();

	protected function setUp() {
		$this->location['sk'] = $this->getContext()->locationRepositoryAccessor->get()->findOneByIso('sk');
		$this->location['hu'] = $this->getContext()->locationRepositoryAccessor->get()->findOneByIso('hu');
		$this->location['usal'] = $this->getContext()->locationRepositoryAccessor->get()->findOneByIso('usal');
		$this->location['ve'] = $this->getContext()->locationRepositoryAccessor->get()->findOneByIso('ve');
		// $this->rentalCreator = $this->getContext()->rentalCreator;
		// $this->user = $this->getContext()->userRepositoryAccessor->get()->findOneByRole(3);
		// $this->rentalName = 'Chata Chalupa';
	}


	public function testFff()
	{
		$normalizer = $this->getContext()->addressNormalizer;
		$info = $normalizer->getInfoUsingGps(new Extras\Types\Latlong(46.800059, -65.983887));

	}

	protected function createAddress($data) {
		$address = $this->getContext()->contactAddressRepositoryAccessor->get()->createNew();
		if (isset($data['latitude']) && isset($data['longitude'])) {
			$address->setGps(new \Extras\Types\LatLong($data['latitude'],$data['longitude']));
			unset($data['latitude'], $data['longitude']);
		}
		foreach ($data as $key => $value) {
			$address->$key = $value;
		}

		return $address;
	}

	public function testNormalizer() {
		$normalizer = $this->getContext()->addressNormalizer;

		// Testing using proper address
		$address = $this->createAddress(array(
			'primaryLocation' => $this->location['sk'],
			'postalCode' => '94651',
			'address' => 'Nová 58',
			'locality' => $this->getContext()->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'nesvady', 'parent' => $this->location)),
		));

		$normalizer->update($address, true);

		$gps = $address->getGps();
		$this->assertSame($gps->getLatitude(), 47.9241334);
		$this->assertSame($gps->getLongitude(), 18.1274789);


		// Testing using GPS
		$address = $this->createAddress(array(
			'primaryLocation' => $this->location['sk'],
			'latitude' => 47.9241334,
			'longitude' => 18.1274789,
		));

		$normalizer->update($address, true);

		$this->assertSame($address->primaryLocation, $this->location['sk']);
		$this->assertSame($address->postalCode, '94651');
		$this->assertSame($address->address, 'Nová 58');
		$this->assertSame($address->locality, $this->getContext()->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'nesvady', 'parent' => $this->location)));
		$this->assertSame($address->subLocality, NULL);



		// Testing using GPS - wrong country
		$address = $this->createAddress(array(
			'primaryLocation' => $this->location['sk'],
			'latitude' => 49.95122,
			'longitude' => 15.996094,
		));

		$result = $normalizer->update($address, true);

		$this->assertSame($result, \Entity\Contact\Address::STATUS_MISPLACED);


		// Testing using GPS - USA - correct
		$address = $this->createAddress(array(
			'primaryLocation' => $this->location['usal'],
			'latitude' => 33.504759,
			'longitude' => -86.764526,
		));


		$result = $normalizer->update($address, true);

		$this->assertSame($address->primaryLocation, $this->location['usal']);
		$this->assertSame($address->postalCode, '35213');
		$this->assertSame($address->address, 'Montclair Rd 3815-3823');
		$this->assertSame($address->locality->slug, 'birmingham');
		$this->assertSame($address->subLocality, NULL);

		// Testing using GPS - Venezuela
		$address = $this->createAddress(array(
			'primaryLocation' => $this->location['ve'],
			'latitude' => 11.144256,
			'longitude' => -63.865585,
		));

		$result = $normalizer->update($address, true);

		$this->assertSame($address->primaryLocation, $this->location['ve']);
		$this->assertSame($address->postalCode, NULL);
		$this->assertSame($address->address, 'Calle La Mira');
		$this->assertSame($address->locality->slug, 'antolin-del-campo');
		$this->assertSame($address->subLocality, 'La Mira');


		// Testing using Address - Sk
		$address = $this->createAddress(array(
			'primaryLocation' => $this->location['sk'],
			'latitude' => 47.9241334,
			'longitude' => 18.1274789,
		));

		$result = $normalizer->update($address, true);

		$this->assertSame($address->primaryLocation, $this->location['sk']);
		$this->assertSame($address->postalCode, '94651');
		$this->assertSame($address->address, 'Nová 58');
		$this->assertSame($address->locality->slug, 'nesvady');
		$this->assertSame($address->subLocality, NULL);

	}
}
