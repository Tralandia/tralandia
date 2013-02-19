<?php
namespace Service\Contact;


use Doctrine\ORM\EntityManager;

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
	 * @param \Entity\Location\Location $primaryLocation
	 * @param string $address
	 * @param string $locality
	 * @param string $postalCode
	 * @param string $latitude
	 * @param string $longitude
	 * @param string $subLocality
	 *
	 * @return \Entity\Contact\Address
	 */
	public function create(\Entity\Location\Location $primaryLocation, $address = '', $locality = '',
						   $postalCode = '', $latitude = '', $longitude = '', $subLocality = '')
	{
		$addressRepository = $this->em->getRepository('\Entity\Contact\Address');
		/** @var $locationRepository \Repository\Location\LocationRepository */
		$locationRepository = $this->em->getRepository('\Entity\Location\Location');

		/** @var $addressEntity \Entity\Contact\Address */
		$addressEntity = $addressRepository->createNew();
		$addressEntity->setPrimaryLocation($primaryLocation);
		$addressEntity->setAddress($address);
		$addressEntity->setPostalCode($postalCode);

		$locality = $locationRepository->findOrCreateLocality($locality, $primaryLocation);
		$addressEntity->setLocality($locality);
		$addressEntity->setSubLocality($subLocality);

		$this->addressNormalizer->update($addressEntity, TRUE);

		return $addressEntity;
	}

}