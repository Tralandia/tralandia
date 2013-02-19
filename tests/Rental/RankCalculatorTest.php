<?php
namespace Tests\Rental;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class RankCalculatorTest extends \Tests\TestCase
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

		$this->amenities[1] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(1);
		$this->amenities[2] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(2);
		$this->amenities[3] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(3);
	}

	public function testRank() {
		$address = $this->getContext()->contactAddressRepositoryAccessor->get()->createNew();
		$address->primaryLocation = $this->location;
		$address->postalCode = '94651';
		$address->address = 'Nová 58';
		$address->locality = $this->getContext()->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'nesvady', 'parent' => $this->location));

		$gps = new \Extras\Types\LatLong(47.924237, 18.127506);
		$address->setGps($gps);
		
		$this->rental = $this->rentalCreator->create($address, $this->user, $this->rentalName);

		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 0);

		$this->rental->classification = 3;
		$this->rental->type = $this->getContext()->rentalTypeRepositoryAccessor->get()->findOneByOldId('103');
		//$this->rental->name->createTranslation($this->languages['sk'], 'Chata Mrož') ;
		$this->rental->teaser->createTranslation($this->languages['sk'], 'Nádherná chata uprostred lesa pri potôčiku.');
		$this->rental->contactName = 'Ja som kontakt';
		$this->rental->phone = $this->getContext()->phoneBook->getOrCreate('+421 902 318 926');
		$this->rental->email = $this->getContext()->contactEmailRepositoryAccessor->get()->createNew()->setValue('toth.radoslav@gmail.com');
		$this->rental->url = $this->getContext()->contactUrlRepositoryAccessor->get()->createNew()->setValue('http://www.unschooling.sk');
		$this->rental->addSpokenLanguage($this->languages['sk']);
		$this->rental->addSpokenLanguage($this->languages['hu']);
		$this->rental->addSpokenLanguage($this->languages['en']);
		$this->rental->addAmenity($this->amenities[1]);
		$this->rental->addAmenity($this->amenities[2]);
		$this->rental->addAmenity($this->amenities[3]);
		$this->rental->checkIn = 14;
		$this->rental->checkOut = 10;
		$this->rental->price = new \Extras\Types\Price(15, $this->getContext()->currencyRepositoryAccessor->get()->findOneByIso('EUR'));

		$this->rental->maxCapacity = 42;
		$this->rental->bedroomCount = 6;
		$this->rental->rooms = '3xAPT (2x3+1)';
		
		// Pricelist Row
		$row = $this->getContext()->rentalPricelistRowRepositoryAccessor->get()->createNew();
		$row->sort = 1;
		$row->roomCount = 3;
		$row->roomType = $this->getContext()->rentalRoomTypeRepositoryAccessor->get()->findOneBySlug('apartment');
		$row->bedCount = 14;
		$row->extraBedCount = 2;

		$this->rental->addPricelistRow($row);

		// Pricelist download
		$pricelist = $this->getContext()->rentalPricelistRepositoryAccessor->get()->createNew();
		$pricelist->name = 'Cennik';
		$pricelist->rental = $this->rental;
		$pricelist->language = $this->languages['sk'];
		//$pricelistDecorator = $this->getContext()->rentalPricelistDecoratorFactory->create($pricelist);
		//$pricelistDecorator->setContentFromFile('http://www.tralandia.sk/u/'.$value[4]);

		$this->rental->addPricelist($pricelist);

		// Interview
		$answer = $this->getContext()->rentalInterviewAnswerRepositoryAccessor->get()->createNew();
		$answer->setQuestion($this->getContext()->rentalInterviewQuestionRepositoryAccessor->get()->findOneById(1));
		$answer->answer->createTranslation($this->languages['sk'], 'Toto je odpoved na prvu otazku, viac vam k tomu neviem dodat.');

		$this->rental->addInterviewAnswer($answer);




		// $this->assertInstanceOf('\Entity\Rental\Rental', $rental);
		// $this->assertSame($this->location->getId(), $rental->getAddress()->getPrimaryLocation()->getId());

		// $language = $this->location->getDefaultLanguage();
		// $this->assertSame($this->rentalName, $rental->getName()->getTranslationText($language));

	}

	protected function assertRank($status, $points) {
		$rentalDecorator = $this->getContext()->rentalDecoratorFactory->create($this->rental);
		$rank = $rentalDecorator->calculateRank();
		d($rank['missing']);
		// $this->assertSame($rank['status'], $status, 'Status nesedi...');
		// $this->assertSame($rank['points'], $points, 'Points nesedi...');
	}
}