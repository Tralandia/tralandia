<?php

namespace Extras\Forms\Container;

use Nette\Application\UI\Link;

class AddressContainer extends BaseContainer
{

	/**
	 * @var \Entity\Contact\Address
	 */
	protected $address;

	/**
	 * @param array|\Traversable $locations
	 * @param \Entity\Contact\Address $address
	 */
	public function __construct($locations, \Entity\Contact\Address $address = NULL)
	{
		parent::__construct();

		$this->address = $address;

		$this->addText('address', '#Address');
		$this->addText('locality', '#Locality');
		$this->addText('postalCode', '#Postal Code');
		$this->addSelect('location', '#Primary location', $locations);
		$this->addHidden('latitude');
		$this->addHidden('longitude');

		$this->setDefaultValues();
	}

	public function getMainControl()
	{
		return $this['address'];
	}

	protected function setDefaultValues()
	{
		if($this->address) {
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
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->address->getPrimaryLocation();
	}

}
