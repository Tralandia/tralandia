<?php
namespace Tests\FormHandler;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class RegistrationHandlerTest extends \Tests\TestCase
{

	/**
	 * @var \FormHandler\RegistrationHandler
	 */
	public $registrationHandler;

	/**
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	public $rentalDecoratorFactory;

	/**
	 * @var \Extras\Books\Phone
	 */
	protected $phoneBook;


	protected function setUp()
	{
		$this->phoneBook = $this->getContext()->phoneBook;
		$this->registrationHandler = $this->getContext()->registrationHandler;
		$this->rentalDecoratorFactory = $this->getContext()->rentalDecoratorFactory;
	}


	/**
	 * Return base valid data
	 * @return \Nette\ArrayHash
	 */
	public function getValidData()
	{
		$data = [
			'country' => 56,
			'language' => 144,

			//'referrer' => 'luzbo',
			'email' => Nette\Utils\Strings::random(5) . '@email.com',
			'password' => 'adsfasdf',
			'url' => 'google.com',
			'phone' => [
				'prefix' => '421',
				'number' => '908 123 789',
				'phone' => $this->phoneBook->getOrCreate('421 908 123 789'),
			],
			'rental' => [
				'name' => 'Chata Test',
				'price' => '3',
				'maxCapacity' => 15,
				'type' => [
					'type' => 3,
					'classification' => 2,
				],

				'board' => [287],
				'ownerAvailability' => 275,
				'pet' => 296,
				'placement' => [1],
				'important' => [50, 188],

				'address' => [
					'address' => 'Ľ. Štúra 8, Nové Zámky, Slovakia',
				],
				'checkIn' => 8,
				'checkOut' => 9,
			],
		];


		$data = \Nette\ArrayHash::from($data);

		return $data;
	}


	public function testValidData()
	{
		$data = $this->getValidData();
		$clonedData = clone $data;
		$handler = $this->registrationHandler;
		$rental = $handler->handleSuccess($data);

		$this->assertInstanceOf('\Entity\Rental\Rental', $rental);
		$rentalDecorator = $this->rentalDecoratorFactory->create($rental);
		$rentalDecorator->calculateRank();

		$data = $clonedData;
		$this->assertEquals($data['country'], $rental->getPrimaryLocation()->getId());
		$this->assertEquals($data['language'], $rental->getEditLanguage()->getId());
		$this->assertEquals($data['email'], $rental->getEmail()->getValue());
		$this->assertEquals($data['email'], $rental->getOwner()->getLogin());
		$this->assertEquals($data['password'], $rental->getOwner()->getPassword());
		$this->assertEquals($data['url'], $rental->getUrl()->getValue());
		$this->assertEquals($data['rental']['ownerAvailability'], $rental->getOwnerAvailability()->getId());
		$this->assertEquals($data['rental']['pet'], $rental->getPetAmenity()->getId());

		$phone = '+' . $data['phone']['prefix'] . ' ' . $data['phone']['number'];
		$this->assertEquals($phone, $rental->getPhone()->getInternational());

		//$this->assertEquals($data['rental']['name'], $rental->getName());
		$this->assertEquals($data['rental']['price'], $rental->getPrice()->getSourceAmount());
	}

}
