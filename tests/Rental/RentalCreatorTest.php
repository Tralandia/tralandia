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

	/**
	 * @var \Entity\User\User
	 */
	public $user;

	protected function setUp() {
		$this->rentalCreator = $this->getContext()->rentalCreator;
		$this->location = $this->getContext()->locationRepositoryAccessor->get()->findOneByOldId(46);
		$this->user = $this->getContext()->userRepositoryAccessor->get()->findOneByRole(3);
		$this->rentalName = 'Chata Chalupa';
	}

	public function testCreate() {
//		$rental = $this->rentalCreator->create($this->location, $this->user, $this->rentalName);
//
//		$this->assertInstanceOf('\Entity\Rental\Rental', $rental);
//		$this->assertSame($this->location->getId(), $rental->getAddress()->getPrimaryLocation()->getId());
//
//		$language = $this->location->getDefaultLanguage();
//		$this->assertSame($this->rentalName, $rental->getName()->getTranslationText($language));

	}

}