<?php

namespace Entity\Location;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Location\LocationRepository")
 * @ORM\Table(name="location", indexes={@ORM\index(name="name", columns={"name_id"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="latitude", columns={"latitude"}), @ORM\index(name="longitude", columns={"longitude"})})
 * @EA\Primary(key="id", value="slug")
 * @EA\Generator(skip="{setSlug, getParent}")
*/
class Location extends \Entity\BaseEntityDetails {

	const STATUS_DRAFT = 'draft';
	const STATUS_LAUNCHED = 'launched';

	/**
	 * @var Boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $isPrimary = FALSE;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"}, fetch="EAGER")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameOfficial;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameShort;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location", cascade={"persist"})
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
	 * @ORM\OneToMany(targetEntity="Entity\Rental\Rental", mappedBy="primaryLocation")
	 */
	protected $primaryRentals;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Address", inversedBy="locations")
	 */
	protected $addresses;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Seo\BackLink", mappedBy="location")
	 */
	protected $backLinks;

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
	 * @ORM\ManyToOne(targetEntity="Entity\Currency", cascade={"persist"})
	 */
	protected $defaultCurrency;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language", cascade={"persist"})
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
		return (bool)$this->isPrimary;
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

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->primaryRentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->addresses = new \Doctrine\Common\Collections\ArrayCollection;
		$this->backLinks = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Location\Location
	 */
	public function setIsPrimary($isPrimary)
	{
		$this->isPrimary = $isPrimary;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getIsPrimary()
	{
		return $this->isPrimary;
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
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Location\Location
	 */
	public function setNameOfficial(\Entity\Phrase\Phrase $nameOfficial)
	{
		$this->nameOfficial = $nameOfficial;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getNameOfficial()
	{
		return $this->nameOfficial;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Location\Location
	 */
	public function setNameShort(\Entity\Phrase\Phrase $nameShort)
	{
		$this->nameShort = $nameShort;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getNameShort()
	{
		return $this->nameShort;
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
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Location\Location
	 */
	public function setLatitude(\Extras\Types\Latlong $latitude)
	{
		$this->latitude = $latitude;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
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
	 * @return \Entity\Location\Location
	 */
	public function setLongitude(\Extras\Types\Latlong $longitude)
	{
		$this->longitude = $longitude;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
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
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Location\Location
	 */
	public function addPrimaryRental(\Entity\Rental\Rental $primaryRental)
	{
		if(!$this->primaryRentals->contains($primaryRental)) {
			$this->primaryRentals->add($primaryRental);
		}
		$primaryRental->setPrimaryLocation($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Location\Location
	 */
	public function removePrimaryRental(\Entity\Rental\Rental $primaryRental)
	{
		$this->primaryRentals->removeElement($primaryRental);
		$primaryRental->unsetPrimaryLocation();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getPrimaryRentals()
	{
		return $this->primaryRentals;
	}
		
	/**
	 * @param \Entity\Contact\Address
	 * @return \Entity\Location\Location
	 */
	public function addAddresse(\Entity\Contact\Address $addresse)
	{
		if(!$this->addresses->contains($addresse)) {
			$this->addresses->add($addresse);
		}

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Address
	 * @return \Entity\Location\Location
	 */
	public function removeAddresse(\Entity\Contact\Address $addresse)
	{
		$this->addresses->removeElement($addresse);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Contact\Address
	 */
	public function getAddresses()
	{
		return $this->addresses;
	}
		
	/**
	 * @param \Entity\Seo\BackLink
	 * @return \Entity\Location\Location
	 */
	public function addBackLink(\Entity\Seo\BackLink $backLink)
	{
		if(!$this->backLinks->contains($backLink)) {
			$this->backLinks->add($backLink);
		}
		$backLink->setLocation($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Seo\BackLink
	 * @return \Entity\Location\Location
	 */
	public function removeBackLink(\Entity\Seo\BackLink $backLink)
	{
		$this->backLinks->removeElement($backLink);
		$backLink->unsetLocation();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Seo\BackLink
	 */
	public function getBackLinks()
	{
		return $this->backLinks;
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