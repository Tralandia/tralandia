<?php
namespace Tests\HarvestRegistration;

use Image\RentalImageManager;
use Nette, Extras;
use Routers\BaseRoute;
use Tests\DataIntegrity\PhoneTest;
use Kdyby\Doctrine\EntityManager;
use Tests\TestCase;
use Service\Contact;
use Nette\DateTime;
use Tralandia\Harvester;


/**
 * @backupGlobals disabled
 */
class MainTest extends TestCase
{
	/**
	 * @var array
	 */
	public $objectData;
	/**
	 * @var array
	 */
	public $goodData;

	/**
	 * @var \Service\Contact\AddressNormalizer
	 */
	private $addressNormalizer;
	/**
	 * @var \Extras\Books\Phone
	 */
	private $phone;
	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;
	/**
	 * @var \Image\RentalImageManager
	 */
	private $rm;
	/**
	 * @var \Service\Rental\RentalCreator
	 */
	protected $rentalCreator;

	/**
	 * @var \User\UserCreator
	 */
	protected $userCreator;

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;



	protected function setUp()
	{
		$this->addressNormalizer = $this->getContext()->addressNormalizer;
//		$this->em = $this->getContext()->getByType('\Tralandia\Harvester\RegistrationData');
		$this->em = $this->getContext()->getByType('\Doctrine\ORM\EntityManager');
		$this->phone = $this->getContext()->getByType('\Extras\Books\Phone');
		$this->rm = $this->getContext()->getByType('\Image\RentalImageManager');

		$this->rentalCreator = $this->getContext()->rentalCreator;
		$this->userCreator = $this->getContext()->userCreator;
		$this->environment = $this->getContext()->getByType('\Environment\Environment');

		$this->objectData = [
			'email' => 'kmark1@wp.pl',
			'phone' => $this->phone->getOrCreate('+421 905 64/45/45'),
			'name' => 'Góry Bystrzyckie',
			'primaryLocation' => 'sk',
			'maxCapacity' => 32,
			'type' => 'hotel',
			'classification' => 3,
			'address' => 'P. Blahu 1, Nové Zámky, Slovakia',
			'gps' => ['47.9817899','18.1618206'],
			'contactName' => 'Konrad Markowski',
			'url' => 'ttp://www.gorybystrzyckie.agrowakacje.pl/',
			'spokenLanguage' => ['sk', 'cs', 'en'],
			'checkIn' => 8,
			'checkOut' => 10,
			'price' => ['amount' => 10, 'currency' => 'EUR'],
			'description' => 'popis objektu',
			'images' => ['https://a1.muscache.com/pictures/21707951/medium.jpg', 'https://a1.muscache.com/pictures/21707951/medium.jpg'],
			'bedroomCount' => 3,
			'lastUpdate' => '2013-12-23 20:20:12'
		];
	}
	public function testBase()
	{
		$processingData = new Harvester\ProcessingData($this->addressNormalizer, $this->phone, $this->em);
		$process = $processingData->process($this->objectData);
		$registrationData = new Harvester\RegistrationData($this->rentalCreator, $this->environment, $this->em, $this->rm);
		$outputData = $registrationData->registration($process);
	}

}
