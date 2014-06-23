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
//		$this->rentalCreator = $this->getContext()->rentalCreator;
//		$this->location = $this->getContext()->locationRepositoryAccessor->get()->findOneByOldId(46);
//		$this->user = $this->getContext()->userRepositoryAccessor->get()->findOneByRole(3);
//		$this->rentalName = 'Chata Mrož';
//
//		$this->languages['sk'] = $this->getContext()->languageRepositoryAccessor->get()->findOneByIso('sk');
//		$this->languages['en'] = $this->getContext()->languageRepositoryAccessor->get()->findOneByIso('en');
//		$this->languages['hu'] = $this->getContext()->languageRepositoryAccessor->get()->findOneByIso('hu');
//		$this->languages['de'] = $this->getContext()->languageRepositoryAccessor->get()->findOneByIso('de');
//
//		$this->amenities[1] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(1);
//		$this->amenities[2] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(2);
//		$this->amenities[3] = $this->getContext()->rentalAmenityRepositoryAccessor->get()->findOneById(3);
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
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 9);

		$this->rental->classification = 3;
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 10);

		$this->rental->type = $this->getContext()->rentalTypeRepositoryAccessor->get()->findOneBySlug('hotel');
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 11);

		$this->rental->teaser->createTranslation($this->languages['sk'], 'Nádherná chata uprostred lesa pri potôčiku.');
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 14);

		$this->rental->contactName = 'Ja som kontakt';
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 15);

		$this->rental->phone = $this->getContext()->phoneBook->getOrCreate('+421 902 318 926');
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 17);

		$this->rental->email = $this->getContext()->contactEmailRepositoryAccessor->get()->createNew()->setValue('toth.radoslav@gmail.com');
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 18);

		$this->rental->url = 'http://www.unschooling.sk';
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 19);

		$this->rental->addSpokenLanguage($this->languages['sk']);
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 20);

		$this->rental->addSpokenLanguage($this->languages['hu']);
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 21);

		$this->rental->addSpokenLanguage($this->languages['en']);
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 22);

		$this->rental->addSpokenLanguage($this->languages['de']);
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 22);

		$this->rental->addAmenity($this->amenities[1]);
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 23);

		$this->rental->addAmenity($this->amenities[2]);
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 24);

		$this->rental->addAmenity($this->amenities[3]);
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 25);

		$this->rental->checkIn = 14;
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 26);

		$this->rental->checkOut = 10;
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 27);

		$this->rental->price = new \Extras\Types\Price(15, $this->getContext()->currencyRepositoryAccessor->get()->findOneByIso('EUR'));
		$this->assertRank(\Entity\Rental\Rental::STATUS_DRAFT, 30);

		$this->rental->maxCapacity = 42;
		$this->assertRank(\Entity\Rental\Rental::STATUS_LIVE, 31);


		$this->rental->bedroomCount = 6;
		$this->assertRank(\Entity\Rental\Rental::STATUS_LIVE, 32);

		$this->rental->rooms = '3xAPT (2x3+1)';
		$this->assertRank(\Entity\Rental\Rental::STATUS_LIVE, 33);


		// Pricelist Row
		$row = $this->getContext()->rentalPricelistRowRepositoryAccessor->get()->createNew();
		$row->sort = 1;
		$row->roomCount = 3;
		$row->roomType = $this->getContext()->rentalRoomTypeRepositoryAccessor->get()->findOneBySlug('apartment');
		$row->bedCount = 14;
		$row->extraBedCount = 2;

		$this->rental->addPricelistRow($row);
		$this->assertRank(\Entity\Rental\Rental::STATUS_LIVE, 38);


		// Pricelist download
		$pricelist = $this->getContext()->rentalPricelistRepositoryAccessor->get()->createNew();
		$pricelist->name = 'Cennik';
		$pricelist->rental = $this->rental;
		$pricelist->language = $this->languages['sk'];
		//$pricelistDecorator = $this->getContext()->rentalPricelistDecoratorFactory->create($pricelist);
		//$pricelistDecorator->setContentFromFile('http://www.tralandia.sk/u/'.$value[4]);

		$this->rental->addPricelist($pricelist);
		$this->assertRank(\Entity\Rental\Rental::STATUS_LIVE, 43);

		// Interview
		$answer = $this->getContext()->rentalInterviewAnswerRepositoryAccessor->get()->createNew();
		$answer->setQuestion($this->getContext()->rentalInterviewQuestionRepositoryAccessor->get()->findOneById(1));
		$answer->answer->createTranslation($this->languages['sk'], 'Toto je odpoved na prvu otazku, viac vam k tomu neviem dodat.');

		$this->rental->addInterviewAnswer($answer);
		$this->assertRank(\Entity\Rental\Rental::STATUS_LIVE, 44);
	}

	protected function assertRank($status, $points) {
		$rentalDecorator = $this->getContext()->rentalDecoratorFactory->create($this->rental);
		$rank = $rentalDecorator->calculateRank();
		//d($rank);
		//d($rank['missing']);
		//d($rank['complete']);
		$this->assertSame($rank['status'], $status, 'Status nesedi...');
		$this->assertSame($rank['points'], $points, 'Points nesedi...');
	}


	public function testRental()
	{
		/** @var $calculator \Tralandia\Rental\RankCalculator */
		$calculator = $this->getContext()->getByType('\Tralandia\Rental\RankCalculator');

		$rental = $this->findRental(45948);
		$calculator->updateRank($rental);

		$this->assertEquals(\Entity\Rental\Rental::STATUS_LIVE, $rental->getStatus());
		$r = 1;
	}
}
