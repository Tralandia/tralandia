<?php

namespace Extras\Forms\Container;

use Entity\Rental\Rental;
use Entity\Contact\Address;
use Entity\Location\Location;
use Extras\Types\Latlong;
use Nette\InvalidArgumentException;
use Service\Contact\AddressCreator;
use Nette\Localization\ITranslator;
use Tralandia\BaseDao;
use Tralandia\Location\Locations;

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
	 * @var Address
	 */
	protected $addressEntity;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $addressDao;

	/**
	 * @var \Tralandia\Location\Locations
	 */
	private $locations;


	/**
	 * @param Address|Location $addressOrLocation
	 * @param \Service\Contact\AddressCreator $addressCreator
	 * @param \Tralandia\BaseDao $addressDao
	 * @param \Tralandia\Location\Locations $locations
	 * @param \Nette\Localization\ITranslator $translator
	 *
	 * @throws \Nette\InvalidArgumentException
	 */
	public function __construct($addressOrLocation, AddressCreator $addressCreator, BaseDao $addressDao, Locations $locations, ITranslator $translator)
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

		$this->addHidden('entityId');

		$this->setDefaultValues();

		$this->onValidate[] = callback($this, 'validate');
		$this->addressDao = $addressDao;
		$this->locations = $locations;
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
		if($address instanceof Address && $address->isValid()) {
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
		$address = $this->getAddressEntity();
		return (int) (($address instanceof Address && $address->isValid()) || $this->getForm()->isSubmitted());
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
		$defaults = [];
		if($this->address) {
			$locality = $this->address->getLocality();
			$defaults = [
				'city' => $locality ? $this->translator->translate($locality->getName()) : NULL,
				'latitude' => $this->address->getGps()->getLatitude(),
				'longitude' => $this->address->getGps()->getLongitude(),
				'entityId' => $this->address->getId(),
			];
			$formattedAddress = $this->address->getFormattedAddress();
			$defaults['address'] = $formattedAddress;
		}

		if(!isset($defaults['latitude'])) $defaults['latitude'] = $this->primaryLocation->getGps()->getLatitude();
		if(!isset($defaults['longitude'])) $defaults['longitude'] = $this->primaryLocation->getGps()->getLongitude();
		$this->setDefaults($defaults);
	}


	public function setValues($values, $erase = FALSE)
	{
		if(!$values) return NULL;

		if($values instanceof Address) {
			$locality = $this->address->getLocality();
			$valuesTemp = [
				'address' => $values->getFormattedAddress(),
				'city' => $locality ? $this->translator->translate($locality->getName()) : NULL,
				'latitude' => $values->getGps()->getLatitude(),
				'longitude' => $values->getGps()->getLongitude(),
				'entityId' => $values->getId(),
			];
			$values = $valuesTemp;
		}

		parent::setValues($values, $erase);
	}


	public function getFormattedValues($asArray = FALSE)
	{
		$return = $asArray ? array() : new \Nette\ArrayHash;

		if(!$this->addressEntity) {
			$formattedAddress = $this['address']->getValue();
			$city = $this['city']->getValue();
			$latitude = $this['latitude']->getValue();
			$longitude = $this['longitude']->getValue();
			$entityId = $this['entityId']->getValue();
			if($formattedAddress) {
				if($entityId) {
					$address = $this->addressDao->find($entityId);
					$locality = $this->locations->findOrCreateLocality($city, $this->primaryLocation);
					$address->setFormattedAddress($formattedAddress);
					$address->setLocality($locality);
					$address->setGps(new Latlong($latitude, $longitude));
				} else {
					$address = $this->addressCreator->create($formattedAddress, $city, $this->primaryLocation, new Latlong($latitude, $longitude));
				}
				$return['addressEntity'] = $address;
				$this->addressEntity = $address;
			} else {
				$return['addressEntity'] = NULL;
			}
		} else {
			$return['addressEntity'] = $this->addressEntity;
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
