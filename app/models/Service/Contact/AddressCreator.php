<?php
namespace Service\Contact;


use Doctrine\ORM\EntityManager;
use Entity\Location\Location;
use Extras\Types\Latlong;
use Nette\Utils\Arrays;
use Tralandia\Location\Locations;

class AddressCreator
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var AddressNormalizer
	 */
	protected $addressNormalizer;

	/**
	 * @var \Tralandia\Location\Locations
	 */
	private $locations;


	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param AddressNormalizer $addressNormalizer
	 */
	public function __construct(EntityManager $em, AddressNormalizer $addressNormalizer, Locations $locations)
	{
		$this->em = $em;
		$this->addressNormalizer = $addressNormalizer;
		$this->locations = $locations;
	}


	/**
	 * @param string $address
	 * @param string $city
	 * @param \Entity\Location\Location $primaryLocation
	 * @param \Extras\Types\Latlong $gps
	 *
	 * @return \Entity\Contact\Address
	 */
	public function create($address, $city,Location $primaryLocation, Latlong $gps)
	{
		$addressRepository = $this->em->getRepository('\Entity\Contact\Address');

		$locality = $this->locations->findOrCreateLocality($city, $primaryLocation);

		/** @var $addressEntity \Entity\Contact\Address */
		$addressEntity = $addressRepository->createNew();
		$addressEntity->setPrimaryLocation($primaryLocation);
//		$addressEntity->setAddress($info[AddressNormalizer::ADDRESS]);
//		$addressEntity->setPostalCode($info[AddressNormalizer::POSTAL_CODE]);
		$addressEntity->setFormattedAddress($address);

		$addressEntity->setLocality($locality);
//		$addressEntity->setSubLocality($info[AddressNormalizer::SUBLOCALITY]);

//		$gps = new \Extras\Types\Latlong($info[AddressNormalizer::LATITUDE], $info[AddressNormalizer::LONGITUDE]);
		$addressEntity->setGps($gps);

		return $addressEntity;
	}


	public function validate($address, Latlong $gps) {

		$info = $this->addressNormalizer->getInfoUsingAddress($address);

		if(!$info) {
			$info = $this->addressNormalizer->getInfoUsingGps($gps);
		}

		return $info;
	}
}
