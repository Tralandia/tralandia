<?php
namespace Tests\Rental;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class AddressNormalizerTest extends \Tests\TestCase
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

	public function testRank() {
		$address = $this->getContext()->contactAddressRepositoryAccessor->get()->createNew();
		$address->primaryLocation = $this->location;
		$address->postalCode = '94651';
		$address->address = 'Nová 58';
		$address->locality = $this->getContext()->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'nesvady', 'parent' => $this->location));

		$gps = new \Extras\Types\LatLong(47.924237, 18.127506);
		$address->setGps($gps);
		
		$rental = $this->rentalCreator->create($address, $this->user, $this->rentalName);
		$rental->classification = 3;
		$rental->type => ;
		$rental->name => ;
		$rental->teaser => ;
		$rental->contactName => ;
		$rental->phones => ;
		$rental->emails => ;
		$rental->urls => ;
		$rental->spokenLanguages => ;
		$rental->amenities => ;
		$rental->tags => ;
		$rental->checkIn => ;
		$rental->checkOut => ;
		$rental->price => ;
		$rental->pricelistRows => ;
		$rental->pricelists => ;
		$rental->interviewAnswers => ;
		$rental->maxCapacity => ;
		$rental->bedroomsCount => ;
		$rental->rooms => ;




		$this->assertInstanceOf('\Entity\Rental\Rental', $rental);
		$this->assertSame($this->location->getId(), $rental->getAddress()->getPrimaryLocation()->getId());

		$language = $this->location->getDefaultLanguage();
		$this->assertSame($this->rentalName, $rental->getName()->getTranslationText($language));

	}
}