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
	}

	public function getMainControl()
	{
		return $this['address'];
	}

	public function getZoom()
	{
		return $this->address->getPrimaryLocation()->getDefaultZoom();
	}

	public function setDefaultValues()
	{
		if($this->address) {
			$locality = $this->getForm()->getTranslator()->translate($this->address->getLocality()->getName());
			$defaults = [
				'address' => $this->address->getAddress(),
				'locality' => $locality,
				'postalCode' => $this->address->getPostalCode(),
				'primaryLocation' => $this->address->getPrimaryLocation()->getId(),
				'latitude' => $this->address->getGps()->getLatitude(),
				'longitude' => $this->address->getGps()->getLongitude(),
			];
		} else {
			$defaults = [
				'latitude' => $this->address->getPrimaryLocation()->getGps()->getLatitude(),
				'longitude' => $this->address->getPrimaryLocation()->getGps()->getLongitude(),
			];
		}

		$this->setDefaults($defaults);
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->address->getPrimaryLocation();
	}

}
