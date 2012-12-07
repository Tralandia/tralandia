<?php

namespace Entity\Location;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Location\LocationRepository")
 * @ORM\Table(name="location", indexes={@ORM\index(name="name", columns={"name_id"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="latitude", columns={"latitude"}), @ORM\index(name="longitude", columns={"longitude"})})
 * @EA\Primary(key="id", value="slug")
 */
class Location extends \Entity\BaseEntityDetails {

	const STATUS_DRAFT = 'draft';
	const STATUS_LAUNCHED = 'launched';

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
	 * @var slug
	 * @ORM\Column(type="slug")
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
	protected $polygon;

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
	 * @ORM\ManyToMany(targetEntity="Entity\Company\BankAccount", inversedBy="countries")
	 */
	protected $bankAccounts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Company\Company", inversedBy="countries")
	 */
	protected $companies;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Company\Office", inversedBy="countries")
	 */
	protected $offices;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Domain", inversedBy="locations")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Invoice\Marketing", inversedBy="locations")
	 */
	protected $marketings;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Rental\Rental", mappedBy="primaryLocation")
	 */
	protected $primaryRentals;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="locations")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\User\User", mappedBy="location", cascade={"persist"})
	 */
	protected $users;

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
	 * @var contacts
	 * @ORM\Column(type="contacts", nullable=true)
	 */
	protected $contacts;

								//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->companies = new \Doctrine\Common\Collections\ArrayCollection;
		$this->offices = new \Doctrine\Common\Collections\ArrayCollection;
		$this->marketings = new \Doctrine\Common\Collections\ArrayCollection;
		$this->primaryRentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->users = new \Doctrine\Common\Collections\ArrayCollection;
		$this->backLinks = new \Doctrine\Common\Collections\ArrayCollection;
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
	 * @param slug
	 * @return \Entity\Location\Location
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;

		return $this;
	}
		
	/**
	 * @return slug|NULL
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
	 * @return \Entity\Location\Location|NULL
	 */
	public function getParent()
	{
		return $this->parent;
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
	public function setPolygon($polygon)
	{
		$this->polygon = $polygon;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPolygon()
	{
		$this->polygon = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPolygon()
	{
		return $this->polygon;
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
	 * @param \Entity\Company\BankAccount
	 * @return \Entity\Location\Location
	 */
	public function addBankAccount(\Entity\Company\BankAccount $bankAccount)
	{
		if(!$this->bankAccounts->contains($bankAccount)) {
			$this->bankAccounts->add($bankAccount);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Company\BankAccount
	 */
	public function getBankAccounts()
	{
		return $this->bankAccounts;
	}
		
	/**
	 * @param \Entity\Company\Company
	 * @return \Entity\Location\Location
	 */
	public function addCompany(\Entity\Company\Company $company)
	{
		if(!$this->companies->contains($company)) {
			$this->companies->add($company);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Company\Company
	 */
	public function getCompanies()
	{
		return $this->companies;
	}
		
	/**
	 * @param \Entity\Company\Office
	 * @return \Entity\Location\Location
	 */
	public function addOffice(\Entity\Company\Office $office)
	{
		if(!$this->offices->contains($office)) {
			$this->offices->add($office);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Company\Office
	 */
	public function getOffices()
	{
		return $this->offices;
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
	 * @param \Entity\Invoice\Marketing
	 * @return \Entity\Location\Location
	 */
	public function addMarketing(\Entity\Invoice\Marketing $marketing)
	{
		if(!$this->marketings->contains($marketing)) {
			$this->marketings->add($marketing);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoice\Marketing
	 */
	public function getMarketings()
	{
		return $this->marketings;
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
		if($this->primaryRentals->contains($primaryRental)) {
			$this->primaryRentals->removeElement($primaryRental);
		}
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
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Location\Location
	 */
	public function addRental(\Entity\Rental\Rental $rental)
	{
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getRentals()
	{
		return $this->rentals;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Location\Location
	 */
	public function addUser(\Entity\User\User $user)
	{
		if(!$this->users->contains($user)) {
			$this->users->add($user);
		}
		$user->setLocation($this);

		return $this;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Location\Location
	 */
	public function removeUser(\Entity\User\User $user)
	{
		if($this->users->contains($user)) {
			$this->users->removeElement($user);
		}
		$user->unsetLocation();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\User\User
	 */
	public function getUsers()
	{
		return $this->users;
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
		if($this->backLinks->contains($backLink)) {
			$this->backLinks->removeElement($backLink);
		}
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
	 * @param \Extras\Types\Contacts
	 * @return \Entity\Location\Location
	 */
	public function setContacts(\Extras\Types\Contacts $contacts)
	{
		$this->contacts = $contacts;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetContacts()
	{
		$this->contacts = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Contacts|NULL
	 */
	public function getContacts()
	{
		return $this->contacts;
	}
}