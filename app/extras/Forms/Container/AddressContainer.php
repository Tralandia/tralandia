<?php

namespace Extras\Forms\Container;

use Nette\Application\UI\Link;

class AddressContainer extends BaseContainer
{

	/**
	 * @var \Entity\Contact\Address
	 */
	protected $address;

	public function __construct($locations, \Entity\Contact\Address $address)
	{
		parent::__construct();

		$this->address = $address;

		$this->addText('address', '#Address');
		$this->addText('locality', '#Locality');
		$this->addText('postalCode', '#Postal Code');
		$this->addSelect('location', '#Primary location', $locations);
		$this->addHidden('latitude');
		$this->addHidden('longitude');
	}

	public function getMainControl()
	{
		return $this['address'];
	}

	public function setDefaultValues()
	{
		$locality = $this->getForm()->getTranslator()->translate($this->address->getLocality()->getName());
		$this->setDefaults([
			'address' => $this->address->getAddress(),
			'locality' => $locality,
			'postalCode' => $this->address->getPostalCode(),
			'primaryLocation' => $this->address->getPrimaryLocation()->getId(),
			'latitude' => $this->address->getGps()->getLatitude(),
			'longitude' => $this->address->getGps()->getLongitude(),
		]);
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->address->getPrimaryLocation();
	}

}
