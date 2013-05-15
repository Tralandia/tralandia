<?php
namespace Tests\FormHandler;

use Nette, Extras;
use \Nette\ArrayHash;

/**
 * @backupGlobals disabled
 */
class EditFormHandlerTest extends \Tests\TestCase
{

	/**
	 * @var \FormHandler\EditFormHandler
	 */
	public $rentalEditHandler;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $currencyRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalPlacementRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalTypeRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalPricelistRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalPricelistRowRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $contactPhoneRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $languageRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalAmenityRepositoryAccessor;

	/**
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	public $rentalDecoratorFactory;

	/**
	 * @var \Extras\Books\Phone
	 */
	protected $phoneBook;

	/**
	 * @var \Service\Contact\AddressCreator
	 */
	protected $addressCreator;


	protected function setUp()
	{
		$this->phoneBook = $this->getContext()->phoneBook;
		$this->addressCreator = $this->getContext()->addressCreator;
		$this->rentalEditHandler = $this->getContext()->rentalEditHandlerFactory->create($this->findRental(10));
		$this->rentalDecoratorFactory = $this->getContext()->rentalDecoratorFactory;
		$this->currencyRepositoryAccessor = $this->getContext()->currencyRepositoryAccessor;
		$this->rentalPlacementRepositoryAccessor = $this->getContext()->rentalPlacementRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $this->getContext()->rentalTypeRepositoryAccessor;
		$this->rentalPricelistRepositoryAccessor = $this->getContext()->rentalPricelistRepositoryAccessor;
		$this->rentalPricelistRowRepositoryAccessor = $this->getContext()->rentalPricelistRowRepositoryAccessor;
		$this->contactPhoneRepositoryAccessor = $this->getContext()->contactPhoneRepositoryAccessor;
		$this->languageRepositoryAccessor = $this->getContext()->languageRepositoryAccessor;
		$this->rentalAmenityRepositoryAccessor = $this->getContext()->rentalAmenityRepositoryAccessor;
	}


	/**
	 * Return base valid data
	 * @return \Nette\ArrayHash
	 */
	public function getValidData()
	{
		$data = ArrayHash::from([
			'address' => [
				'address' => "Tatranská Štrba 3132",
				'location' => "56",
				'latitude' => "49.06386417102",
				'longitude' => "20.056182861328",
				'addressEntity' => $this->addressCreator->create("Tatranská Štrba 3132"),
			],
			'placement' => $this->rentalPlacementRepositoryAccessor->get()->find(12),
			'type' => [
				'type' => $this->rentalTypeRepositoryAccessor->get()->find(1),
				'classification' => 0,
			],
			'checkIn' => rand(0, 23),
			'checkOut' => rand(0, 23),
			'maxCapacity' => rand(2, 4),
			'separateGroups' => TRUE,
			'ownerAvailability' => $this->rentalPricelistRowRepositoryAccessor->get()->find(277),
			'pet' => $this->rentalPricelistRowRepositoryAccessor->get()->find(297),
			'photos' => [
				'sort' => [13,12,14],
				'images' => [],
			],
			'priceList' => [
				'list' => [
					0 => [
						'roomCount' => 1,
						'roomType' => 1,
						'bedCount' => 1,
						'extraBedCount' => 0,
						'price' => 12,
						'entity' => $this->rentalPricelistRowRepositoryAccessor->get()->find(1),
					],
					1 => [
						'roomCount' => 1,
						'roomType' => 2,
						'bedCount' => 4,
						'extraBedCount' => 2,
						'price' => 1,
						'entity' => $this->rentalPricelistRowRepositoryAccessor->get()->find(2),
					],
					2 => [
						'roomCount' => 1,
						'roomType' => 2,
						'bedCount' => 8,
						'extraBedCount' => 3,
						'price' => 1,
						'entity' => $this->rentalPricelistRowRepositoryAccessor->get()->find(3),
					]
				]
			],
			'priceUpload' => [
				'list' => [
					0 => [
						'name' => "",
						'language' => 0,
						'file' => NULL,
						'entity' => $this->rentalPricelistRepositoryAccessor->get()->find(1)
					],
					1 => [
						'name' => "",
						'language' => 0,
						'file' => NULL,
						'entity' => $this->rentalPricelistRepositoryAccessor->get()->find(2)
					]
				]
			],
			'phone' => [
				'prefix' => 421,
				'number' => "903 618 998",
				'entity' => $this->contactPhoneRepositoryAccessor->get()->find(440663),
			],
			'url' => "http://test.sk",
			'price' => [
				'value' => 14,
				'entity' => new \Extras\Types\Price(14, $this->currencyRepositoryAccessor->get()->find(1)),
			],
			// 'translationLanguage' => $this->languageRepositoryAccessor->get()->find(144),
			'name' => [
				'nl' => "",
				'sk' => "SK Test Name",
				'no' => "",
				'vi' => "",
				'zh' => "",
			],
			'teaser' => [
				'bg' => "",
				'sk' => "SK Teaser Text",
				'no' => "",
				'zh' => "",
			],
			'interview' => [
				1 => [
					'sk' => 'interview test 1'
				],
				2 => [
					'sk' => 'interview test 2'
				],
				3 => [
					'sk' => 'interview test 3'
				],
				4 => [
					'sk' => 'interview test 4'
				],
				5 => [
					'sk' => 'interview test 5'
				],
				6 => [
					'sk' => 'interview test 6'
				],
				7 => [
					'sk' => 'interview test 7'
				],
				8 => [
					'sk' => 'interview test 8'
				],
				9 => [
					'sk' => 'interview test 9'
				],
				10 => [
					'sk' => 'interview test 10'
				],
			],
			'bedroomCount' => 3,
			'rooms' => "2x3",
			'board' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(284),
			],
			'children' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(32),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(33),
			],
			'activity' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(1)
			],
			'relax' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(120),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(121),
			],
			'service' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(162),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(163),
			],
			'wellness' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(184),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(185),
			],
			'congress' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(239),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(240),
			],
			'kitchen' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(75),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(76),
			],
			'bathroom' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(100),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(101),
			],
			'heating' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(109),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(110),
			],
			'parking' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(119)
			],
			'room' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(41),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(42),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(43),
			],
			'other' => [
				0 => $this->rentalAmenityRepositoryAccessor->get()->find(2),
				1 => $this->rentalAmenityRepositoryAccessor->get()->find(15),
				2 => $this->rentalAmenityRepositoryAccessor->get()->find(16),
				3 => $this->rentalAmenityRepositoryAccessor->get()->find(22),
				4 => $this->rentalAmenityRepositoryAccessor->get()->find(23)
			],
		]);

		return $data;
	}


	public function testValidData()
	{
		$data = $this->getValidData();
		$handler = $this->rentalEditHandler;
		$rental = $handler->handleSuccess($data);

		$this->assertInstanceOf('\Entity\Rental\Rental', $rental);

		// @TODO: nejde to kvoli tomu limitu na geocoding api
		// $this->assertEquals(
			// $data['address']->address,
			// $rental->getAddress()->address
		// );

		$this->assertEquals(
			$data['placement']->slug,
			$rental->getPlacement()->slug
		);

		$this->assertEquals(
			$data['type']->classification,
			$rental->getClassification()
		);

		$this->assertEquals(
			$data['type']->type->slug,
			$rental->getType()->slug
		);

		$this->assertEquals(
			$data['checkIn'],
			$rental->getCheckIn()
		);

		$this->assertEquals(
			$data['checkOut'],
			$rental->getCheckOut()
		);

		$this->assertEquals(
			$data['maxCapacity'],
			$rental->getMaxCapacity()
		);

		$this->assertEquals(
			$data['separateGroups'],
			$rental->getSeparateGroups()
		);

		$this->assertEquals(
			$data['ownerAvailability']->id,
			$rental->getOwnerAvailability()->id
		);

		$this->assertEquals(
			$data['pet']->id,
			$rental->getPetAmenity()->id
		);

		$firstPricelistRow = current($data['priceList']->list);
		$firstPricelistRow2 = array_values($rental->getPricelistRows()->toArray());
		$firstPricelistRow2 = array_shift($firstPricelistRow2);
		$this->assertEquals(
			$firstPricelistRow->entity->id,
			$firstPricelistRow2->id
		);

		// $firstPricelist = current($data['priceUpload']->list);
		// $firstPricelist2 = array_values($rental->getPricelists()->toArray());
		// $firstPricelist2 = array_shift($firstPricelist2);
		// $this->assertEquals(
		// 	$firstPricelist->name,
		// 	$firstPricelist2->name
		// );

		$this->assertEquals(
			$data['phone']->entity->international,
			$rental->getPhone()->international
		);

		$this->assertEquals(
			$data['url'],
			$rental->getUrl()
		);

		$this->assertEquals(
			$data['price']->value,
			$rental->getPrice()->sourceAmount
		);

		$language = $this->languageRepositoryAccessor->get()->find(144); // sk
		$this->assertEquals(
			$data['name']['sk'],
			$rental->name->getTranslation($language)->translation
		);

		$this->assertEquals(
			$data['teaser']['sk'],
			$rental->teaser->getTranslation($language)->translation
		);

		foreach ($rental->interviewAnswers as $answer) {
			$this->assertEquals(
				$data['interview'][$answer->question->id]['sk'],
				$answer->answer->getTranslation($this->languageRepositoryAccessor->get()->find(144))->translation
			);
		}

	}

}
