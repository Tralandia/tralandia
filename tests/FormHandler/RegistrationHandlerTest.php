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

	protected function setUp() {
		$this->registrationHandler = $this->getContext()->registrationHandler;
	}

	/**
	 * Return base valid data
	 * @return \Nette\ArrayHash
	 */
	public function getValidData() {

		$data = new \Nette\ArrayHash;

		$data->country = 46;
		$data->language = 144;

		$data->referrer = 'david@gmail.sk';

		$data->email = 'test@email.com';
		$data->phone = '+421908123456';
		$data->url = 'www.google.com';
		$data->password = 'df34kdj4se4jr33';

		$data->rentalName = 'Chata pri lese';
		$data->rentalType = 1;
		$data->rentalClassification = 3;
		$data->rentalPrice = 5;
		$data->rentalMaxCapacity = 22;

		$data->package = 1;

		$data->legalForm = TRUE;
		$data->clientName = 'Test Testovic';
		$data->clientCompanyName = 'Test s.r.o.';
		$data->clientAddress1 = 'Ulica 2';
		$data->clientAddress2 = '/444';
		$data->clientLocality = 'Nesvady';
		$data->clientPostalCode = '12345';
		$data->clientVatPayer = TRUE;
		$data->clientCompanyVatId1 = 'SK';
		$data->clientCompanyVatId2 = '3453483292443';
		$data->clientCompanyId = '23432434';
		$data->clientCountry = $data->country;

		return $data;
	}

	public function testValidData()
	{
		$data = $this->getValidData();
		$handler = $this->registrationHandler;
		$rental = $handler->handleSuccess($data);

		$this->assertInstanceOf('\Entity\Rental\Rental', $rental);
	}

}