<?php

namespace Entity\Contact;

use Nette\Utils\Arrays;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_address")
 *
 */
class Address extends \Entity\BaseEntity {

	const STATUS_UNCHECKED = 'unchecked';
	const STATUS_MISPLACED = 'misplaced';
	const STATUS_OK = 'ok';
	const STATUS_INCOMPLETE = 'incomplete';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $status = self::STATUS_INCOMPLETE;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $address;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location", cascade={"persist"})
	 */
	protected $primaryLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location", cascade={"persist"})
	 */
	protected $locality;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location")
	 * @ORM\JoinTable(name="_location_address",
	 *      joinColumns={@ORM\JoinColumn(name="address_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="location_id", referencedColumnName="id")}
	 *      )
	 */
	protected $locations;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $latitude;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $longitude;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $formattedAddress;

	/**
	 * #nepouzivane od 31/12/2013
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $postalCode;


	/**
	 * #nepouzivane od 31/12/2013
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $subLocality;



	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Contact\Address
	 */
	public function setGps(\Extras\Types\Latlong $latlong)
	{
		$this->latitude = $latlong->getLatitude();
		$this->longitude = $latlong->getLongitude();

		return $this;
	}

	/**
	 * @return \Extras\Types\Latlong
	 */
	public function getGps()
	{
		return new \Extras\Types\Latlong($this->latitude, $this->longitude);
	}

	/**
	 * @param NULL
	 * @return \Entity\Contact\Address
	 */
	public function clearLocations()
	{
//		foreach ($this->locations as $value) {
//			$value->removeAddress($this);
//		}
		$this->locations->clear();

		return $this;
	}

	/**
	 * @param array[\Entity\Location\Location]
	 * @return \Entity\Contact\Address
	 */
	public function setLocations(array $locations)
	{
		$this->clearLocations();
		foreach ($locations as $value) {
			if ($value instanceof \Entity\Location\Location) {
				$this->addLocation($value);
			}
		}

		return $this;
	}

	public function getLocationsByType($types, $limit = NULL) {
		$returnJustOneType = NULL;
		if(!is_array($types)) {
			$returnJustOneType = $types;
			$types = array($types);
		}

		$return = array();
		$i = 0;
		foreach ($this->getLocations as $location) {
			if(!empty($location->type) && in_array($location->type->slug, $types)) {
				$return[$location->type->slug][] = $location;
				$i++;
			}

			if(is_numeric($limit)) {
				if($i == $limit) break;
			}
		}

		if($returnJustOneType) {

			$return = Arrays::get($return,$returnJustOneType,array());
		}

		return $return;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */


	public function __construct()
	{
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param string
	 * @return \Entity\Contact\Address
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param string
	 * @return \Entity\Contact\Address
	 */
	public function setAddress($address)
	{
		$this->address = $address;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Address
	 */
	public function unsetAddress()
	{
		$this->address = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @param string
	 * @return \Entity\Contact\Address
	 */
	public function setFormattedAddress($formattedAddress)
	{
		$this->formattedAddress = $formattedAddress;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Address
	 */
	public function unsetFormattedAddress()
	{
		$this->formattedAddress = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getFormattedAddress()
	{
		return $this->formattedAddress;
	}

	/**
	 * @param string
	 * @return \Entity\Contact\Address
	 */
	public function setPostalCode($postalCode)
	{
		$this->postalCode = $postalCode;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Address
	 */
	public function unsetPostalCode()
	{
		$this->postalCode = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getPostalCode()
	{
		return $this->postalCode;
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Contact\Address
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Address
	 */
	public function unsetPrimaryLocation()
	{
		$this->primaryLocation = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Contact\Address
	 */
	public function setLocality(\Entity\Location\Location $locality)
	{
		$this->locality = $locality;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Address
	 */
	public function unsetLocality()
	{
		$this->locality = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getLocality()
	{
		return $this->locality;
	}

	/**
	 * @param string
	 * @return \Entity\Contact\Address
	 */
	public function setSubLocality($subLocality)
	{
		$this->subLocality = $subLocality;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Address
	 */
	public function unsetSubLocality()
	{
		$this->subLocality = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getSubLocality()
	{
		return $this->subLocality;
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Contact\Address
	 */
	public function addLocation(\Entity\Location\Location $location)
	{
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}

		return $this;
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Contact\Address
	 */
	public function removeLocation(\Entity\Location\Location $location)
	{
		$this->locations->removeElement($location);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Location\Location[]
	 */
	public function getLocations()
	{
		return $this->locations;
	}
}
