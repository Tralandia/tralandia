<?php

namespace Entity\Location;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\NestedSet\MultipleRootNode;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_location")
 */
class Location extends \Entity\BaseEntityDetails implements MultipleRootNode {

	const STATUS_LAUNCHED = 'launched';

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameOfficial;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameShort;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $parentId;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $nestedLeft;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $nestedRight;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $nestedRoot;

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
	 * @ORM\ManyToMany(targetEntity="Entity\Invoicing\Marketing", inversedBy="locations")
	 */
	protected $marketings;

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
	 * @ORM\OneToMany(targetEntity="Traveling", mappedBy="destinationLocation")
	 */
	protected $incomingLocations;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Traveling", mappedBy="sourceLocation")
	 */
	protected $outgoingLocations;


	/* ----------------------------- attributes from country ----------------------------- */

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $status;

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
	 * @ORM\ManyToMany(targetEntity="Entity\Currency", mappedBy="locations", cascade={"persist"})
	 */
	protected $currencies;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language", cascade={"persist"})
	 */
	protected $defaultLanguage;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Dictionary\Language", mappedBy="locations", cascade={"persist"})
	 */
	protected $languages;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $population;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $phonePrefix;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact", cascade={"persist"})
	 */
	protected $facebookGroup;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $capitalCity;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact", cascade={"persist"})
	 */
	protected $phoneNumberEmergency;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact", cascade={"persist"})
	 */
	protected $phoneNumberPolice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact", cascade={"persist"})
	 */
	protected $phoneNumberMedical;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact", cascade={"persist"})
	 */
	protected $phoneNumberFire;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact", cascade={"persist"})
	 */
	protected $wikipediaLink;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Contact", mappedBy="locations", cascade={"persist"})
	 */
	protected $contacts;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $drivingSide;

	/**
	 * @var price
	 * @ORM\Column(type="price", nullable=true)
	 */
	protected $pricesPizza;

	/**
	 * @var price
	 * @ORM\Column(type="price", nullable=true)
	 */
	protected $pricesDinner;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $airports;



	/* ----------------------------- Nested Set Methods - DO NOT DELETE ----------------------------- */

	public function getLeftValue() { return $this->nestedLeft; }
	public function setLeftValue($nestedLeft) { $this->nestedLeft = $nestedLeft; }

	public function getRightValue() { return $this->nestedRight; }
	public function setRightValue($nestedRight) { $this->nestedRight = $nestedRight; }

	public function getRootValue() { return $this->nestedRoot; }
	public function setRootValue($nestedRoot) { $this->nestedRoot = $nestedRoot; }

	public function __toString() { return (string)$this->slug; }


















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->companies = new \Doctrine\Common\Collections\ArrayCollection;
		$this->offices = new \Doctrine\Common\Collections\ArrayCollection;
		$this->marketings = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->users = new \Doctrine\Common\Collections\ArrayCollection;
		$this->incomingLocations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->outgoingLocations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->currencies = new \Doctrine\Common\Collections\ArrayCollection;
		$this->languages = new \Doctrine\Common\Collections\ArrayCollection;
		$this->contacts = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Location\Location
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Location\Location
	 */
	public function setNameOfficial(\Entity\Dictionary\Phrase $nameOfficial) {
		$this->nameOfficial = $nameOfficial;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getNameOfficial() {
		return $this->nameOfficial;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Location\Location
	 */
	public function setNameShort(\Entity\Dictionary\Phrase $nameShort) {
		$this->nameShort = $nameShort;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getNameShort() {
		return $this->nameShort;
	}
		
	/**
	 * @param slug
	 * @return \Entity\Location\Location
	 */
	public function setSlug($slug) {
		$this->slug = $slug;

		return $this;
	}
		
	/**
	 * @return slug|NULL
	 */
	public function getSlug() {
		return $this->slug;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Location\Location
	 */
	public function setParentId($parentId) {
		$this->parentId = $parentId;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetParentId() {
		$this->parentId = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getParentId() {
		return $this->parentId;
	}
		
	/**
	 * @param \Entity\Location\Type
	 * @return \Entity\Location\Location
	 */
	public function setType(\Entity\Location\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param json
	 * @return \Entity\Location\Location
	 */
	public function setPolygon($polygon) {
		$this->polygon = $polygon;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPolygon() {
		$this->polygon = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPolygon() {
		return $this->polygon;
	}
		
	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Location\Location
	 */
	public function setLatitude(\Extras\Types\Latlong $latitude) {
		$this->latitude = $latitude;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetLatitude() {
		$this->latitude = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Latlong|NULL
	 */
	public function getLatitude() {
		return $this->latitude;
	}
		
	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Location\Location
	 */
	public function setLongitude(\Extras\Types\Latlong $longitude) {
		$this->longitude = $longitude;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetLongitude() {
		$this->longitude = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Latlong|NULL
	 */
	public function getLongitude() {
		return $this->longitude;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Location\Location
	 */
	public function setDefaultZoom($defaultZoom) {
		$this->defaultZoom = $defaultZoom;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDefaultZoom() {
		$this->defaultZoom = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getDefaultZoom() {
		return $this->defaultZoom;
	}
		
	/**
	 * @param json
	 * @return \Entity\Location\Location
	 */
	public function setClickMapData($clickMapData) {
		$this->clickMapData = $clickMapData;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetClickMapData() {
		$this->clickMapData = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getClickMapData() {
		return $this->clickMapData;
	}
		
	/**
	 * @param \Entity\Company\BankAccount
	 * @return \Entity\Location\Location
	 */
	public function addBankAccount(\Entity\Company\BankAccount $bankAccount) {
		if(!$this->bankAccounts->contains($bankAccount)) {
			$this->bankAccounts->add($bankAccount);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Company\BankAccount
	 */
	public function getBankAccounts() {
		return $this->bankAccounts;
	}
		
	/**
	 * @param \Entity\Company\Company
	 * @return \Entity\Location\Location
	 */
	public function addCompany(\Entity\Company\Company $company) {
		if(!$this->companies->contains($company)) {
			$this->companies->add($company);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Company\Company
	 */
	public function getCompanies() {
		return $this->companies;
	}
		
	/**
	 * @param \Entity\Company\Office
	 * @return \Entity\Location\Location
	 */
	public function addOffice(\Entity\Company\Office $office) {
		if(!$this->offices->contains($office)) {
			$this->offices->add($office);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Company\Office
	 */
	public function getOffices() {
		return $this->offices;
	}
		
	/**
	 * @param \Entity\Domain
	 * @return \Entity\Location\Location
	 */
	public function setDomain(\Entity\Domain $domain) {
		$this->domain = $domain;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDomain() {
		$this->domain = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Domain|NULL
	 */
	public function getDomain() {
		return $this->domain;
	}
		
	/**
	 * @param \Entity\Invoicing\Marketing
	 * @return \Entity\Location\Location
	 */
	public function addMarketing(\Entity\Invoicing\Marketing $marketing) {
		if(!$this->marketings->contains($marketing)) {
			$this->marketings->add($marketing);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoicing\Marketing
	 */
	public function getMarketings() {
		return $this->marketings;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Location\Location
	 */
	public function addRental(\Entity\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Location\Location
	 */
	public function addUser(\Entity\User\User $user) {
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
	public function removeUser(\Entity\User\User $user) {
		if($this->users->contains($user)) {
			$this->users->removeElement($user);
		}
		$user->unsetLocation();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\User\User
	 */
	public function getUsers() {
		return $this->users;
	}
		
	/**
	 * @param \Entity\Location\Traveling
	 * @return \Entity\Location\Location
	 */
	public function addIncomingLocation(\Entity\Location\Traveling $incomingLocation) {
		if(!$this->incomingLocations->contains($incomingLocation)) {
			$this->incomingLocations->add($incomingLocation);
		}
		$incomingLocation->setDestinationLocation($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Traveling
	 * @return \Entity\Location\Location
	 */
	public function removeIncomingLocation(\Entity\Location\Traveling $incomingLocation) {
		if($this->incomingLocations->contains($incomingLocation)) {
			$this->incomingLocations->removeElement($incomingLocation);
		}
		$incomingLocation->unsetDestinationLocation();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Traveling
	 */
	public function getIncomingLocations() {
		return $this->incomingLocations;
	}
		
	/**
	 * @param \Entity\Location\Traveling
	 * @return \Entity\Location\Location
	 */
	public function addOutgoingLocation(\Entity\Location\Traveling $outgoingLocation) {
		if(!$this->outgoingLocations->contains($outgoingLocation)) {
			$this->outgoingLocations->add($outgoingLocation);
		}
		$outgoingLocation->setSourceLocation($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Traveling
	 * @return \Entity\Location\Location
	 */
	public function removeOutgoingLocation(\Entity\Location\Traveling $outgoingLocation) {
		if($this->outgoingLocations->contains($outgoingLocation)) {
			$this->outgoingLocations->removeElement($outgoingLocation);
		}
		$outgoingLocation->unsetSourceLocation();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Traveling
	 */
	public function getOutgoingLocations() {
		return $this->outgoingLocations;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setStatus($status) {
		$this->status = $status;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetStatus() {
		$this->status = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getStatus() {
		return $this->status;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setIso($iso) {
		$this->iso = $iso;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetIso() {
		$this->iso = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getIso() {
		return $this->iso;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setIso3($iso3) {
		$this->iso3 = $iso3;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetIso3() {
		$this->iso3 = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getIso3() {
		return $this->iso3;
	}
		
	/**
	 * @param \Entity\Currency
	 * @return \Entity\Location\Location
	 */
	public function setDefaultCurrency(\Entity\Currency $defaultCurrency) {
		$this->defaultCurrency = $defaultCurrency;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDefaultCurrency() {
		$this->defaultCurrency = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency|NULL
	 */
	public function getDefaultCurrency() {
		return $this->defaultCurrency;
	}
		
	/**
	 * @param \Entity\Currency
	 * @return \Entity\Location\Location
	 */
	public function addCurrency(\Entity\Currency $currency) {
		if(!$this->currencies->contains($currency)) {
			$this->currencies->add($currency);
		}
		$currency->addLocation($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Currency
	 * @return \Entity\Location\Location
	 */
	public function removeCurrency(\Entity\Currency $currency) {
		if($this->currencies->contains($currency)) {
			$this->currencies->removeElement($currency);
		}
		$currency->removeLocation($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Currency
	 */
	public function getCurrencies() {
		return $this->currencies;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Location\Location
	 */
	public function setDefaultLanguage(\Entity\Dictionary\Language $defaultLanguage) {
		$this->defaultLanguage = $defaultLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDefaultLanguage() {
		$this->defaultLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getDefaultLanguage() {
		return $this->defaultLanguage;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Location\Location
	 */
	public function addLanguage(\Entity\Dictionary\Language $language) {
		if(!$this->languages->contains($language)) {
			$this->languages->add($language);
		}
		$language->addLocation($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Location\Location
	 */
	public function removeLanguage(\Entity\Dictionary\Language $language) {
		if($this->languages->contains($language)) {
			$this->languages->removeElement($language);
		}
		$language->removeLocation($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Dictionary\Language
	 */
	public function getLanguages() {
		return $this->languages;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Location\Location
	 */
	public function setPopulation($population) {
		$this->population = $population;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPopulation() {
		$this->population = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getPopulation() {
		return $this->population;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setPhonePrefix($phonePrefix) {
		$this->phonePrefix = $phonePrefix;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPhonePrefix() {
		$this->phonePrefix = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPhonePrefix() {
		return $this->phonePrefix;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Location\Location
	 */
	public function setFacebookGroup(\Entity\Contact\Contact $facebookGroup) {
		$this->facebookGroup = $facebookGroup;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetFacebookGroup() {
		$this->facebookGroup = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getFacebookGroup() {
		return $this->facebookGroup;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setCapitalCity($capitalCity) {
		$this->capitalCity = $capitalCity;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetCapitalCity() {
		$this->capitalCity = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCapitalCity() {
		return $this->capitalCity;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Location\Location
	 */
	public function setPhoneNumberEmergency(\Entity\Contact\Contact $phoneNumberEmergency) {
		$this->phoneNumberEmergency = $phoneNumberEmergency;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPhoneNumberEmergency() {
		$this->phoneNumberEmergency = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getPhoneNumberEmergency() {
		return $this->phoneNumberEmergency;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Location\Location
	 */
	public function setPhoneNumberPolice(\Entity\Contact\Contact $phoneNumberPolice) {
		$this->phoneNumberPolice = $phoneNumberPolice;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPhoneNumberPolice() {
		$this->phoneNumberPolice = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getPhoneNumberPolice() {
		return $this->phoneNumberPolice;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Location\Location
	 */
	public function setPhoneNumberMedical(\Entity\Contact\Contact $phoneNumberMedical) {
		$this->phoneNumberMedical = $phoneNumberMedical;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPhoneNumberMedical() {
		$this->phoneNumberMedical = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getPhoneNumberMedical() {
		return $this->phoneNumberMedical;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Location\Location
	 */
	public function setPhoneNumberFire(\Entity\Contact\Contact $phoneNumberFire) {
		$this->phoneNumberFire = $phoneNumberFire;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPhoneNumberFire() {
		$this->phoneNumberFire = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getPhoneNumberFire() {
		return $this->phoneNumberFire;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Location\Location
	 */
	public function setWikipediaLink(\Entity\Contact\Contact $wikipediaLink) {
		$this->wikipediaLink = $wikipediaLink;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetWikipediaLink() {
		$this->wikipediaLink = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getWikipediaLink() {
		return $this->wikipediaLink;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Location\Location
	 */
	public function addContact(\Entity\Contact\Contact $contact) {
		if(!$this->contacts->contains($contact)) {
			$this->contacts->add($contact);
		}
		$contact->addLocation($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Location\Location
	 */
	public function removeContact(\Entity\Contact\Contact $contact) {
		if($this->contacts->contains($contact)) {
			$this->contacts->removeElement($contact);
		}
		$contact->removeLocation($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Contact\Contact
	 */
	public function getContacts() {
		return $this->contacts;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setDrivingSide($drivingSide) {
		$this->drivingSide = $drivingSide;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetDrivingSide() {
		$this->drivingSide = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDrivingSide() {
		return $this->drivingSide;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Location\Location
	 */
	public function setPricesPizza(\Extras\Types\Price $pricesPizza) {
		$this->pricesPizza = $pricesPizza;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPricesPizza() {
		$this->pricesPizza = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getPricesPizza() {
		return $this->pricesPizza;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Location\Location
	 */
	public function setPricesDinner(\Extras\Types\Price $pricesDinner) {
		$this->pricesDinner = $pricesDinner;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetPricesDinner() {
		$this->pricesDinner = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getPricesDinner() {
		return $this->pricesDinner;
	}
		
	/**
	 * @param string
	 * @return \Entity\Location\Location
	 */
	public function setAirports($airports) {
		$this->airports = $airports;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location
	 */
	public function unsetAirports() {
		$this->airports = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getAirports() {
		return $this->airports;
	}
}