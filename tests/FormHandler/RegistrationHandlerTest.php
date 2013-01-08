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
	 * @var \Repository\Location\LocationRepository
	 */
	public $locationRepository;

	/**
	 * @var \Repository\LanguageRepository
	 */
	public $languageRepository;

	/**
	 * @var \Repository\BaseRepository
	 */
	public $rentalTypeRepository;

	protected function setUp() {
		$this->registrationHandler = $this->getContext()->registrationHandler;
		$this->locationRepository = $this->getContext()->locationRepositoryAccessor->get();
		$this->languageRepository = $this->getContext()->languageRepositoryAccessor->get();
		$this->rentalTypeRepository = $this->getContext()->rentalTypeRepositoryAccessor->get();
	}

	/**
	 * Return base valid data
	 * @return \Nette\ArrayHash
	 */
	public function getValidData() {

		$data = new \Nette\ArrayHash;

		$data->country = $this->locationRepository->findOneByOldId(46);
		$data->language = $this->languageRepository->findOneByOldId(144);

		$data->referrer = 'david@gmail.sk';

		$data->email = 'test@email.com';
		$data->phone = '+421908123456';
		$data->password = 'df34kdj4se4jr33';

		$data->rentalName = 'Chata pri lese';
		$data->rentalType = $this->rentalTypeRepository->findOneById(1);
		$data->rentalClassification = 3;
		$data->rentalPrice = 5;
		$data->rentalMaxCapacity = 22;

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
		$data->clientCompanyId = 'Tuto neviem co ma byt...';
		$data->clientCountry = $data->country;

		return $data;
	}

	public function testValidData()
	{
		$data = $this->getValidData();
		$handler = $this->registrationHandler;
		$handler->handleSuccess($data);
	}

}