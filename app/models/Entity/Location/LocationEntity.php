<?php

namespace Entity\Location;

use Entity\Domain;
use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;
use Routers\FrontRoute;

/**
 * @ORM\Entity(repositoryClass="Repository\Location\LocationRepository")
 * @ORM\Table(name="location", indexes={@ORM\index(name="name", columns={"name_id"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="localName", columns={"localName"}), @ORM\index(name="latitude", columns={"latitude"}), @ORM\index(name="longitude", columns={"longitude"})})
 * @EA\Primary(key="id", value="slug")
 * @EA\Generator(skip="{setSlug, getParent, setLatitude, unsetLatitude, getLatitude, setLongitude, unsetLongitude, getLongitude}")
*/
class Location extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"}, fetch="EAGER")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $localName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $parent;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $polygons;

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
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $defaultZoom;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $clickMapData;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Domain", inversedBy="locations")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Address", inversedBy="locations")
	 */
	protected $addresses;

	/* ----------------------------- attributes from country ----------------------------- */

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iso;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iso3;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $phonePrefix;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $defaultCurrency;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $defaultLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $rentalCount;

	/**
	 * @return bool
	 */
	public function isPrimary() {
		return (bool)($this->getType()->slug == 'country');
	}


	/**
	 * @return bool
	 */
	public function isWorld() {
		return $this->getSlug() == FrontRoute::ROOT_LOCATION_SLUG;
	}


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
	 * @param string|null $slug
	 *
	 * @return Location|null
	 */
	public function getParent($slug = NULL)
	{
		/** @var $parent \Entity\Location\Location | NULL */
		$parent = $this->parent;
		if(!$parent) return NULL;

		if($slug === NULL) {
			return $parent;
		} else if ($slug == $parent->getType()->getSlug()) {
			return $parent;
		} else {
			return $parent->getParent($slug);
		}
	}

	/**
	 * @return Location|null
	 */
	public function getPrimaryParent()
	{
		/** @var $parent \Entity\Location\Location | NULL */
		$parent = $this->parent;
		if(!$parent) return NULL;

		if($parent->isPrimary()) {
			return $parent;
		} else {
			return $parent->getPrimaryParent();
		}
	}


	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}

	/**
	 * @param NULL
	 * @return \Entity\Location\Location
	 */
	public function clearAddresses()
	{
		foreach ($this->addresses as $key => $value) {
			$value->removeLocation($this);
		}
		$this->addresses->clear();

		return $this;
	}

	public function getFlagName()
	{
		$parentIso = $this->getParent()->getIso();
		$name = $parentIso ? : $this->getIso();
		return $name . '.gif';
	}


	/**
	 * @return bool
	 */
	public function hasDomain()
	{
		return $this->getDomain() instanceof Domain;
	}

	/**
	 * @return \Entity\Domain
	 */
	public function getFirstDomain()
	{
		return $this->hasDomain() ? $this->getDomain() : $this->getParent()->getFirstDomain();
	}


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->addresses = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Location\Location
	 */
	public function setName(\Entity\Phrase\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setLocalName($localName)
	{
		$this->localName = $localName;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetLocalName()
	{
		$this->localName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getLocalName()
	{
		return $this->localName;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetSlug()
	{
		$this->slug = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Location\Location
	 */
	public function setParent(\Entity\Location\Location $parent)
	{
		$this->parent = $parent;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetParent()
	{
		$this->parent = NULL;

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Type
	 * @return \Entity\Location\Location
	 */
	public function setType(\Entity\Location\Type $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Type|NULL
	 */
	public function getType()
	{
		return $this->type;
	}
		
	/**
	 * @param json
	 * @return \Entity\Location\Location
	 */
	public function setPolygons($polygons)
	{
		$this->polygons = $polygons;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPolygons()
	{
		$this->polygons = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPolygons()
	{
		return $this->polygons;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Location\Location
	 */
	public function setDefaultZoom($defaultZoom)
	{
		$this->defaultZoom = $defaultZoom;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDefaultZoom()
	{
		$this->defaultZoom = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getDefaultZoom()
	{
		return $this->defaultZoom;
	}
		
	/**
	 * @param json
	 * @return \Entity\Location\Location
	 */
	public function setClickMapData($clickMapData)
	{
		$this->clickMapData = $clickMapData;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetClickMapData()
	{
		$this->clickMapData = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getClickMapData()
	{
		return $this->clickMapData;
	}
		
	/**
	 * @param \Entity\Domain
	 * @return \Entity\Location\Location
	 */
	public function setDomain(\Entity\Domain $domain)
	{
		$this->domain = $domain;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDomain()
	{
		$this->domain = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Domain|NULL
	 */
	public function getDomain()
	{
		return $this->domain;
	}
		
	/**
	 * @param \Entity\Contact\Address
	 * @return \Entity\Location\Location
	 */
	public function addAddress(\Entity\Contact\Address $address)
	{
		if(!$this->addresses->contains($address)) {
			$this->addresses->add($address);
		}

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Address
	 * @return \Entity\Location\Location
	 */
	public function removeAddress(\Entity\Contact\Address $address)
	{
		$this->addresses->removeElement($address);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Contact\Address[]
	 */
	public function getAddresses()
	{
		return $this->addresses;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setIso($iso)
	{
		$this->iso = $iso;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetIso()
	{
		$this->iso = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getIso()
	{
		return $this->iso;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setIso3($iso3)
	{
		$this->iso3 = $iso3;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetIso3()
	{
		$this->iso3 = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getIso3()
	{
		return $this->iso3;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setPhonePrefix($phonePrefix)
	{
		$this->phonePrefix = $phonePrefix;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPhonePrefix()
	{
		$this->phonePrefix = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPhonePrefix()
	{
		return $this->phonePrefix;
	}
		
	/**
	 * @param \Entity\Currency
	 * @return \Entity\Location\Location
	 */
	public function setDefaultCurrency(\Entity\Currency $defaultCurrency)
	{
		$this->defaultCurrency = $defaultCurrency;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDefaultCurrency()
	{
		$this->defaultCurrency = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency|NULL
	 */
	public function getDefaultCurrency()
	{
		return $this->defaultCurrency;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Location\Location
	 */
	public function setDefaultLanguage(\Entity\Language $defaultLanguage)
	{
		$this->defaultLanguage = $defaultLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDefaultLanguage()
	{
		$this->defaultLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getDefaultLanguage()
	{
		return $this->defaultLanguage;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Location\Location
	 */
	public function setRentalCount($rentalCount)
	{
		$this->rentalCount = $rentalCount;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetRentalCount()
	{
		$this->rentalCount = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getRentalCount()
	{
		return $this->rentalCount;
	}
}