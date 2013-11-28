<?php

namespace Extras\Forms\Container;

use Entity\Rental\Rental;
use Entity\Contact\Address;
use Entity\Location\Location;
use Extras\Types\Latlong;
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
	protected $primaryLocation;

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
			$this->primaryLocation = $this->address->getPrimaryLocation();
		} else if($addressOrLocation instanceof Location) {
			$this->primaryLocation = $addressOrLocation;
		} else {
			throw new InvalidArgumentException;
		}


		$this->addText('search', '#Address')
			->getControlPrototype()
			->setPlaceholder('o100091');

		$this->addText('city', '157047')
			->setRequired(' ');
		$this->addText('address', '817')
			->setRequired(' ');

		$this->addHidden('latitude')
					->setAttribute('class','rentalAddressLatitude');
		$this->addHidden('longitude')
					->setAttribute('class','rentalAddressLongitude');

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
			$zoom = 14;
			$locality = $address->getLocality();
			if($locality) {
				$zoom = $locality->getDefaultZoom();
			}
			return $zoom;
		} else {
			return $this->primaryLocation->getDefaultZoom();
		}
	}


	/**
	 * @return int
	 */
	public function shouldShowMarker()
	{
		return (int) ($this->getAddressEntity() instanceof Address || $this->getForm()->isSubmitted());
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
				'city' => $this->translator->translate($this->address->getLocality()->getName()),
				'latitude' => $this->address->getGps()->getLatitude(),
				'longitude' => $this->address->getGps()->getLongitude(),
			];
			$formattedAddress = $this->address->getFormattedAddress();
			$defaults['address'] = $formattedAddress;
		} else {
			$defaults = [
				//'city' => $this->translator->translate($this->primaryLocation->getName()),
				'latitude' => $this->primaryLocation->getGps()->getLatitude(),
				'longitude' => $this->primaryLocation->getGps()->getLongitude(),
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
				'city' => $this->translator->translate($values->getLocality()->getName()),
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
		$city = $this['city']->getValue();
		$latitude = $this['latitude']->getValue();
		$longitude = $this['longitude']->getValue();
		if($address) {
			$address = $this->addressCreator->create($address, $city, $this->primaryLocation, new Latlong($latitude, $longitude));
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
		return $this->primaryLocation;
	}

	public function validate(array $controls = NULL) {
		$values = $this->getFormattedValues();
		if (!$values['addressEntity']) {
			$this->getMainControl()->addError($this->translator->translate('o100134'));
		}
	}

}
