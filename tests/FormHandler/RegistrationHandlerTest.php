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

		$data = [];

		$data['country'] = 46;
		$data['language'] = 144;

		$data['referrer'] = 'david@gmail.sk';

		$data['email'] = 'test@email.com';
		$data['phone']['prefix'] = '+421';
		$data['phone']['number'] = '908123456';
		$data['url'] = 'www.google.com';
		$data['password'] = 'df34kdj4se4jr33';

		$data['rentalAddress'] = 'Nesvady';
		$data['rentalGps'] = '23345234';
		$data['rentalName'] = 'Chata pri lese';
		$data['rentalType'] = 1;
		$data['rentalClassification'] = 3;
		$data['rentalPrice'] = 5;
		$data['rentalMaxCapacity'] = 22;

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