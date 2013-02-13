<?php
namespace Tests\Rental;

use Nette, Extras;
use Service\Contact;

/**
 * @backupGlobals disabled
 */
class AddressNormalizerTest extends \Tests\TestCase
{

	/**
	 * @var \Entity\Location\Location
	 */
	public $location = array();

	protected function setUp() {
		$this->location['sk'] = $this->getContext()->locationRepositoryAccessor->get()->findOneByIso('sk');
		$this->location['hu'] = $this->getContext()->locationRepositoryAccessor->get()->findOneByIso('hu');
		// $this->rentalCreator = $this->getContext()->rentalCreator;
		// $this->user = $this->getContext()->userRepositoryAccessor->get()->findOneByRole(3);
		// $this->rentalName = 'Chata Chalupa';
	}

	protected function createAddress($data) {
		$address = $this->getContext()->contactAddressRepositoryAccessor->get()->createNew();
		if (isset($data['latitude']) && isset($data['longitude'])) {
			$address->setGps(new \Extras\Types\LatLong(47.924086,18.12742));
			unset($data['latitude'], $data['longitude']);
		}
		foreach ($data as $key => $value) {
			$address->$key = $value;
		}

		return $address;
	}

	public function testNormalizer() {
		// Testing using proper address
		$address = $this->createAddress(array(
			'primaryLocation' => $this->location['sk'],
			'postalCode' => '94651',
			'address' => 'Nová 58',
			'locality' => $this->getContext()->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'nesvady', 'parent' => $this->location)),
		));

		$normalizer = $this->getContext()->addressNormalizerFactory->create($address);
		$normalizer->update(true);
		
		$gps = $address->getGps();
		$this->assertSame($gps->getLatitude(), 47.9241334);
		$this->assertSame($gps->getLongitude(), 18.1274789);
		d($address);


		// Testing using GPS
		$address = $this->createAddress(array(
			'primaryLocation' => $this->location['sk'],
			'latitude' => 47.9241334,
			'longitude' => 18.1274789,
		));

		$normalizer = $this->getContext()->addressNormalizerFactory->create($address);
		$normalizer->update(true);
		
		$this->assertSame($address->primaryLocation, $this->location['sk']);
		$this->assertSame($address->postalCode, '94651');
		$this->assertSame($address->address, 'Nová 58');
		$this->assertSame($address->locality, $this->getContext()->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'nesvady', 'parent' => $this->location)));
		$this->assertSame($address->subLocality, NULL);


		// $this->assertInstanceOf('\Entity\Rental\Rental', $rental);
		// $this->assertSame($this->location->getId(), $rental->getAddress()->getPrimaryLocation()->getId());

		// $language = $this->location->getDefaultLanguage();
		// $this->assertSame($this->rentalName, $rental->getName()->getTranslationText($language));

	}
}