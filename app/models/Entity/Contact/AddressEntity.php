<?php

namespace Entity\Contact;

use Nette\Utils\Arrays;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_address")
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
	protected $status;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $address;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $subLocality;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $postalCode;

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
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="addresses")
	 */
	protected $locations;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong", nullable=true)
	 */
	protected $latitude;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong", nullable=true)
	 */
	protected $longitude;


	/**
	 * @param NULL
	 * @return \Entity\Contact\Address
	 */
	public function clearLocations()
	{
		foreach ($this->locations as $key => $value) {
			$value->removeAddresse($this);
		}
		$this->locations->clear();

		return $this;
	}

	/**
	 * @param array of \Entity\Location\Location
	 * @return \Entity\Contact\Address
	 */
	public function setLocations(array $locations)
	{
		$this->clearLocations();
		foreach ($locations as $key => $value) {
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
	 * @param \Entity\Location\Location
	 * @return \Entity\Contact\Address
	 */
	public function addLocation(\Entity\Location\Location $location)
	{
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->addAddresse($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Contact\Address
	 */
	public function removeLocation(\Entity\Location\Location $location)
	{
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->removeAddresse($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations()
	{
		return $this->locations;
	}
		
	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Contact\Address
	 */
	public function setLatitude(\Extras\Types\Latlong $latitude)
	{
		$this->latitude = $latitude;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Address
	 */
	public function unsetLatitude()
	{
		$this->latitude = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Latlong|NULL
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}
		
	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Contact\Address
	 */
	public function setLongitude(\Extras\Types\Latlong $longitude)
	{
		$this->longitude = $longitude;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Address
	 */
	public function unsetLongitude()
	{
		$this->longitude = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Latlong|NULL
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}
}