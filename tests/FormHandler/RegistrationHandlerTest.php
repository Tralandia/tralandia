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

	protected function setUp() {
		$this->registrationHandler = $this->getContext()->registrationHandler;
		$this->rentalDecoratorFactory = $this->getContext()->rentalDecoratorFactory;
	}

	/**
	 * Return base valid data
	 * @return \Nette\ArrayHash
	 */
	public function getValidData() {
		$data = [
			'country' => 46,
			'language' => 144,

			//'referrer' => 'luzbo',
			'email' => Nette\Utils\Strings::random(5).'@email.com',
			'url' => 'google.com',
			'password' => 'adsfasdf',
			'name' => 'Harlem Shake',
			'phone' => [
				'prefix' => '421',
				'number' => '908 123 789'
			],
			'rental' => [
				'name' => 'Chata Test',
				'price' => '3',
				'maxCapacity' => 15,
				'type' => [
					'type' => 3,
					'classification' => 2,
				],
				'pet' => [1],

				'address' => [
					'address' => 'Ľ. Štúra 8, Nové Zámky, Slovakia',
				],
			],
		];


		$data = \Nette\ArrayHash::from($data);
		return $data;
	}

	public function testValidData()
	{
		$data = $this->getValidData();
		$handler = $this->registrationHandler;
		$rental = $handler->handleSuccess($data);

		$this->assertInstanceOf('\Entity\Rental\Rental', $rental);
		$rentalDecorator = $this->rentalDecoratorFactory->create($rental);
		$rentalDecorator->calculateRank();
		$compulsory = $rental->getCompulsoryMissingInformation();
		$i = 0;
	}

}
