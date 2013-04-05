<?php

namespace Extras\Forms\Container;

use Entity\Contact\Address;
use Entity\Location\Location;
use Nette\InvalidArgumentException;
use Service\Contact\AddressCreator;

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
	 * @var \Service\Contact\AddressCreator
	 */
	protected $addressCreator;


	/**
	 * @param Address|Location $addressOrLocation
	 * @param \Service\Contact\AddressCreator $addressCreator
	 *
	 * @throws \Nette\InvalidArgumentException
	 */
	public function __construct($addressOrLocation, AddressCreator $addressCreator)
	{
		parent::__construct();
		$this->addressCreator = $addressCreator;

		if($addressOrLocation instanceof Address) {
			$this->address = $addressOrLocation;
		} else if($addressOrLocation instanceof Location) {
			$this->location = $addressOrLocation;
		} else {
			throw new InvalidArgumentException;
		}


		$this->addText('address', '#Address')
			->getControlPrototype()
				->setPlaceholder('o100091');

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

	public function shouldShowMarker()
	{
		return (int) $this->address;
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


	public function getValues($asArray = FALSE)
	{

		$values = parent::getValues($asArray);
		$address = $values['address'];
		if($address) {
			$address = $this->addressCreator->create($address);
			$values['addressEntity'] = $address;
		} else {
			$values['addressEntity'] = NULL;
		}

		return $values;
	}


	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->address->getPrimaryLocation();
	}

}
