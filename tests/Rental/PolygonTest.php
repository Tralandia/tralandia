<?php
namespace Tests\Rental;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class PolygonTest extends \Tests\TestCase
{

	/**
	 * @var \Service\Rental\RentalCreator
	 */
	public $rentalCreator;

	/**
	 * @var array
	 */
	public $languages = array();

	/**
	 * @var array
	 */
	public $amenities = array();

	/**
	 * @var \Entity\Location\Location
	 */
	public $location;

	/**
	 * @var \Entity\User\User
	 */
	public $user;

	/**
	 * @var \Entity\Rental\Rental
	 */
	public $rental;

	/**
	 * @var String
	 */
	public $rentalName;


	protected function setUp() {
		$this->rentalCreator = $this->getContext()->rentalCreator;
		$this->location = $this->getContext()->locationRepositoryAccessor->get()->findOneByOldId(46);
		$this->user = $this->getContext()->userRepositoryAccessor->get()->findOneByRole(3);
		$this->rentalName = 'Chata Mrož';

		$this->languages['sk'] = $this->getContext()->languageRepositoryAccessor->get()->findOneByIso('sk');
		$this->languages['en'] = $this->getContext()->languageRepositoryAccessor->get()->findOneByIso('en');
		$this->languages['hu'] = $this->getContext()->languageRepositoryAccessor->get()->findOneByIso('hu');
		$this->languages['de'] = $this->getContext()->languageRepositoryAccessor->get()->findOneByIso('de');

		$this->amenities[1] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(1);
		$this->amenities[2] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(2);
		$this->amenities[3] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(3);
	}

	public function testPolygons() {
		$rental = $this->getContext()->rentalRepositoryAccessor->get()->find(1);
		$rental->getAddress()->clearLocations();
		$this->assertSame($rental->getAddress()->getLocations()->count(), 0);
		$this->getContext()->polygonService->setLocationsForRental($rental);
		$this->assertGreaterThan(0, $rental->getAddress()->getLocations()->count());
		// foreach ($rental->getAddress()->getLocations() as $key => $value) {
		// 	d($value->slug);
		// }

		$location = $this->getContext()->locationRepositoryAccessor->get()->findOneBySlug('liptov');
		// foreach ($location->getAddresses() as $key => $value) {
		// 	d($value->address);
		// }
		$location->clearAddresses();
		$this->assertSame($location->getAddresses()->count(), 0);
		$this->getContext()->polygonService->setRentalsForLocation($location);
		$this->assertGreaterThan(0, $location->getAddresses()->count());

		$this->getContext()->model->flush();

	}
}



