<?php
namespace Tests\Rental;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class RentalCreatorTest extends \Tests\TestCase
{

	/**
	 * @var \Service\Rental\RentalCreator
	 */
	public $rentalCreator;

	/**
	 * @var string
	 */
	public $rentalName;

	/**
	 * @var \Entity\Location\Location
	 */
	public $location;

	protected function setUp() {
		$this->rentalCreator = $this->getContext()->rentalCreator;
		$this->location = $this->getContext()->locationRepositoryAccessor->get()->findOneByOldId(46);
		$this->rentalName = 'Chata Chalupa';
	}

	public function testCreate() {
		$rental = $this->rentalCreator->create($this->location, $this->rentalName);

		$this->assertInstanceOf('\Entity\Rental\Rental', $rental);


	}

}