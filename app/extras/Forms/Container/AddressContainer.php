<?php

namespace Extras\Forms\Container;

use Entity\Contact\Address;
use Entity\Location\Location;
use Nette\InvalidArgumentException;

class AddressContainer extends BaseContainer
{

	/**
	 * @var Address
	 */
	protected $address;

	/**
	 * @var Location
	 */
	protected $location;

	/**
	 * @param array|\Traversable $locations
	 * @param Address|Location $addressOrLocation
	 *
	 * @throws \Nette\InvalidArgumentException
	 */
	public function __construct($locations, $addressOrLocation)
	{
		parent::__construct();

		if($addressOrLocation instanceof Address) {
			$this->address = $addressOrLocation;
		} else if($addressOrLocation instanceof Location) {
			$this->location = $addressOrLocation;
		} else {
			throw new InvalidArgumentException;
		}


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
		if($this->address) {
			return $this->address->getPrimaryLocation()->getDefaultZoom();
		} else {
			return $this->location->getDefaultZoom();
		}
	}

	public function setDefaultValues()
	{
		if($this->address) {
			$locality = $this->getForm()->getTranslator()->translate($this->address->getLocality()->getName());
			$defaults = [
				'address' => $this->address->getAddress(),
				'locality' => $locality,
				'postalCode' => $this->address->getPostalCode(),
				'location' => $this->address->getPrimaryLocation()->getId(),
				'latitude' => $this->address->getGps()->getLatitude(),
				'longitude' => $this->address->getGps()->getLongitude(),
			];
		} else {
			$defaults = [
				'location' => $this->location->getId(),
				'latitude' => $this->location->getGps()->getLatitude(),
				'longitude' => $this->location->getGps()->getLongitude(),
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
