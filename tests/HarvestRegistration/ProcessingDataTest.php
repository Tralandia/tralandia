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
class ProcessingDataTest extends TestCase
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
	 * @var \Extras\FileStorage
	 */
	protected $storage;
	/**
	 * @var \Entity\Location\Location[]
	 */
	public $location = array();



	protected function setUp()
    {
        $this->addressNormalizer = $this->getContext()->addressNormalizer;
        $this->em = $this->getContext()->getByType('\Doctrine\ORM\EntityManager');
        $this->phone = $this->getContext()->getByType('\Extras\Books\Phone');
		$this->storage = $this->getContext()->getByType('\Image\RentalImageManager');

		$this->objectData = [
			'email' => 'kmark1@wp.pl',
			'phone' => $this->phone->getOrCreate('+421 905 64/45/45'),
			'name' => 'Góry Bystrzyckie',
			'primaryLocation' => 'cz',
			'maxCapacity' => 32,
			'type' => 'hotel',
			'classification' => 3,
			'address' => 'P. Blahu 1, Nové Zámky, Slovakia',
			'gps' => ['50.2892','16.5578'],
			'contactName' => 'Konrad Markowski',
			'url' => 'ttp://www.gorybystrzyckie.agrowakacje.pl/',
			'spokenLanguage' => ['sk', 'cs', 'en'],
			'checkIn' => 8,
			'checkOut' => 10,
			'price' => ['amount' => 10, 'currency' => 'EUR'],
			'description' => 'popis objektu',
			'images' => ['www.test.sk', 'www.skuska.sk'],
			'bedroomCount' => 3,
			'lastUpdate' => '2013-12-23 20:20:12'
		];
    }
    public function testBase()
    {
		$processingData = new Harvester\ProcessingData($this->addressNormalizer, $this->phone, $this->em, $this->storage);
        $data = $processingData->process($this->objectData);
//		$price = new Extras\Types\Price($this->objectData['price']['amount'],new \Entity\Currency ($this->objectData['price']['currency']));
//		$test = $price->convertToFloat(new \Entity\Currency ($this->objectData['price']['currency']));
    }

    public function getValidData(){
		$this->objectData = [
			'email' => 'kmark1@wp.pl',
			'phone' => $this->phone->getOrCreate('+421 (0) 905 64/45/45'),
			'name' => 'Góry Bystrzyckie',
			'primaryLocation' => 'cz',
			'maxCapacity' => 32,
			'type' => 3,
			'classification' => 3,
			'address' => 'Ľ. Štúra 8, Nové Zámky, Slovakia',
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
    }

	public function testValidData()
	{
		$data = $this->getValidData();

		$this->assertEquals($data['country'], $rental->getPrimaryLocation()->getId());
		$this->assertEquals($data['language'], $rental->getEditLanguage()->getId());
		$this->assertEquals($data['email'], $rental->getEmail()->getValue());
		$this->assertEquals($data['email'], $rental->getOwner()->getLogin());
		$this->assertEquals($data['url'], $rental->getUrl()->getValue());

		$phone = '+' . $data['phone']['prefix'] . ' ' . $data['phone']['number'];
		$this->assertEquals($phone, $rental->getPhone()->getInternational());

		//$this->assertEquals($data['rental']['name'], $rental->getName());
		$this->assertEquals($data['rental']['price'], $rental->getPrice()->getSourceAmount());
	}

    public function testPhone(){
        $goodPhone = $this->phone->getOrCreate($this->objectData['phone']);
        $this->assertTrue($goodPhone instanceof Phone);
    }

	public function testGetAddress($data = 'Ľ. Štúra 8, Nové Zámky, Slovakia'){
		$address = $this->addressNormalizer->getInfoUsingAddress($data);
	}

    public function testInfo(){
        $gps = ['50.2892','16.5578'];
        if($gps == null){
            $data['address'] = $this->addressNormalizer->getInfoUsingAddress($this->objectData['address']);
        } else {
            $data['gps'] = $this->addressNormalizer->getInfoUsingGps(new Extras\Types\Latlong($this->objectData['gps'][0], $this->objectData['gps'][1]));
        }
    }

    public function testAddress($objectData = NULL){
        $address = $this->createAddress(array(
            'primaryLocation' => $this->objectData['primaryLocation'],
            'address' => isset($this->objectData['address']) ? $this->objectData['address'] : null,
            'latitude' => isset($this->objectData['gps'][0]) ? $this->objectData['gps'][0] : null,
            'longitude' => isset($this->objectData['gps'][1]) ? $this->objectData['gps'][1] : null,
        ));
    }

    public function testGetPrice($price = ['amount' => 10, 'currency' => 'EUR']){
		$price = new Extras\Types\Price($price['amount'], $price['currency']);
		$test = $price->convertToFloat('hu');

    }

    public function createAddress($data) {
        $addressDao = $this->getEm()->getRepository(ADDRESS_ENTITY);
        $address = $addressDao->createNew();
//        if (isset($data['latitude']) && isset($data['longitude'])) {
            $address->setGps(new Extras\Types\LatLong($data['latitude'],$data['longitude']));
//            unset($data['latitude'], $data['longitude']);
//        }
        foreach ($data as $key => $value) {
            $address->$key = $value;
        }
        return $address;
    }


}
