<?php
namespace Tests\HarvestRegistration;

use Environment\Environment;
use Service\Rental\RentalCreator;
use Doctrine\ORM\EntityManager;
use User\UserCreator;
use Tests\TestCase;
use Tralandia\Harvester;


/**
 * @backupGlobals disabled
 */
class RegistrationDataTest extends TestCase
{
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

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;



    protected function setUp()
    {
		$this->rentalCreator = $this->getContext()->rentalCreator;
		$this->userCreator = $this->getContext()->userCreator;
		$this->environment = $this->getContext()->getByType('\Environment\Environment');
		$this->em = $this->getContext()->getByType('\Doctrine\ORM\EntityManager');
    }
    public function testBase()
    {
        $registrationData = new Harvester\RegistrationData($this->rentalCreator, $this->userCreator, $this->environment, $this->em);
        $data = $registrationData->registration($this->getValidData());
    }
	/**
	* Return base valid data
	* @return array
	*/
	public function getValidData()
	{
		$data = [
			'email' => 'kmark1@wp.pl',
			'phone' => '+421 905 64/45/45',
			'name' => 'Góry Bystrzyckie',
			'primaryLocation' => 'cz',
			'maxCapacity' => 32,
			'type' => 3,
			'classification' => 3,
			'address' => 'Mesto ulica cislo',
			'gps' => ['50.2892','16.5578'],
			'contactName' => 'Konrad Markowski',
			'url' => 'ttp://www.gorybystrzyckie.agrowakacje.pl/',
			'spokenLanguage' => ['sk', 'cs', 'en'],
			'checkIn' => 8,
			'checkOut' => 10,
			'price' => ['amount' => 10, 'currency' => 'EUR'],
			'description' => 'popis objektu',
			'images' => null,
			'bedroomCount' => 3,
			'lastUpdate' => '2013-12-23 20:20:12'
		];


		return $data;
	}



}
