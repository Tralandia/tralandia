<?php
namespace Service\Contact;


use Doctrine\ORM\EntityManager;
use Nette\Utils\Arrays;

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
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param AddressNormalizer $addressNormalizer
	 */
	public function __construct(EntityManager $em, AddressNormalizer $addressNormalizer)
	{
		$this->em = $em;
		$this->addressNormalizer = $addressNormalizer;
	}


	/**
	 * @param string $address
	 *
	 * @return \Entity\Contact\Address
	 */
	public function create($address)
	{
		$info = $this->addressNormalizer->getInfoUsingAddress($address);

		$addressRepository = $this->em->getRepository('\Entity\Contact\Address');
		/** @var $locationRepository \Repository\Location\LocationRepository */
		$locationRepository = $this->em->getRepository('\Entity\Location\Location');

		/** @var $addressEntity \Entity\Contact\Address */
		$addressEntity = $addressRepository->createNew();
		$addressEntity->setPrimaryLocation($info[AddressNormalizer::PRIMARY_LOCATION]);
		$addressEntity->setAddress($info[AddressNormalizer::ADDRESS]);
		$addressEntity->setPostalCode($info[AddressNormalizer::POSTAL_CODE]);

		$addressEntity->setLocality($info[AddressNormalizer::LOCALITY]);
		$addressEntity->setSubLocality($info[AddressNormalizer::SUBLOCALITY]);

		$gps = new \Extras\Types\Latlong($info[AddressNormalizer::LATITUDE], $info[AddressNormalizer::LONGITUDE]);
		$addressEntity->setGps($gps);


		return $addressEntity;
	}

}
