<?php

namespace Extras\Forms\Container;

use Entity\Rental\Rental;
use Entity\Contact\Address;
use Entity\Location\Location;
use Nette\InvalidArgumentException;
use Service\Contact\AddressCreator;
use Nette\Localization\ITranslator;

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
	 * @var \Nette\Localization\ITranslator
	 */
	protected $translator;


	/**
	 * @param Address|Location $addressOrLocation
	 * @param \Service\Contact\AddressCreator $addressCreator
	 *
	 * @throws \Nette\InvalidArgumentException
	 */
	public function __construct($addressOrLocation, AddressCreator $addressCreator, ITranslator $translator)
	{
		parent::__construct();
		$this->addressCreator = $addressCreator;
		$this->translator = $translator;

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

		$this->onValidate[] = callback($this, 'validate');
	}

	public function getMainControl()
	{
		return $this['address'];
	}


	/**
	 * @return int
	 */
	public function getZoom()
	{
		$address = $this->getAddressEntity();
		if($address) {
			$zoom = $address->getLocality()->getDefaultZoom();
			return $zoom ? : 14;
		} else {
			return $this->location->getDefaultZoom();
		}
	}


	/**
	 * @return int
	 */
	public function shouldShowMarker()
	{
		return (int) ($this->getAddressEntity() instanceof Address);
	}


	/**
	 * @return Address
	 */
	public function getAddressEntity()
	{
		$address = $this->getValues();
		return isset($address->addressEntity) ? $address->addressEntity : $this->address;
	}


	/**
	 * @param $address
	 */
	public function setDefaultValues($address = NULL)
	{
		if($this->address) {
			$defaults = [
				'location' => $this->address->getPrimaryLocation()->getId(),
				'latitude' => $this->address->getGps()->getLatitude(),
				'longitude' => $this->address->getGps()->getLongitude(),
			];
			$formattedAddress = $this->address->getFormattedAddress();
			$defaults['address'] = $formattedAddress;
		} else {
			$defaults = [
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
				'address' => $values->getFormattedAddress(),
				'location' => $values->getPrimaryLocation()->getId(),
				'latitude' => $values->getGps()->getLatitude(),
				'longitude' => $values->getGps()->getLongitude(),
			];
			$values = $valuesTemp;
		}

		parent::setValues($values, $erase);
	}


	public function getFormattedValues($asArray = FALSE)
	{
		$return = $asArray ? array() : new \Nette\ArrayHash;

		$address = $this['address']->getValue();
		if($address) {
			$address = $this->addressCreator->create($address);
			$return['addressEntity'] = $address;
		} else {
			$return['addressEntity'] = NULL;
		}

		return $return;
	}


	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->address->getPrimaryLocation();
	}

	public function validate() {
		$values = $this->getValues();
		if (!$this->addressCreator->validate($values['address'])) {
			$this->getMainControl()->addError($this->translator->translate('o100134'));
		}
	}

}
