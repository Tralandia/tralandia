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
	 * @param Address|Location $addressOrLocation
	 *
	 * @throws \Nette\InvalidArgumentException
	 */
	public function __construct($addressOrLocation)
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
		$this->addHidden('location');
		$this->addHidden('latitude');
		$this->addHidden('longitude');

		$this->setDefaultValues();
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

	/**
	 * @param $address
	 */
	public function setDefaultValues($address = NULL)
	{
		if($this->address) {
			$defaults = [
				'address' => $this->address->getAddress(),
				'location' => $this->address->getPrimaryLocation()->getId(),
				'latitude' => $this->address->getGps()->getLatitude(),
				'longitude' => $this->address->getGps()->getLongitude(),
			];
		} else {
			$defaults = [
				'address' => $address,
				'location' => $this->location->getId(),
				'latitude' => $this->location->getGps()->getLatitude(),
				'longitude' => $this->location->getGps()->getLongitude(),
			];
		}

		$this->setDefaults($defaults);
	}


	public function setValues($values, $erase = FALSE)
	{
		if(!$values) return NULL;

		if($values instanceof Address) {
			$valuesTemp = [
				'address' => $values->getAddress(),
				'location' => $values->getPrimaryLocation()->getId(),
				'latitude' => $values->getGps()->getLatitude(),
				'longitude' => $values->getGps()->getLongitude(),
			];
			$values = $valuesTemp;
		}
		parent::setValues($values, $erase);
	}


	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->address->getPrimaryLocation();
	}

}
