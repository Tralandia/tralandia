<?php

namespace Entities\Location;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\NestedSet\MultipleRootNode;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_location")
 */
class Location extends \Entities\BaseEntityDetails implements MultipleRootNode {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameOfficial;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
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
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Company\BankAccount", inversedBy="countries")
	 */
	protected $bankAccounts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Company\Company", inversedBy="countries")
	 */
	protected $companies;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Company\Office", inversedBy="countries")
	 */
	protected $offices;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Domain", inversedBy="locations")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Invoicing\Marketing", inversedBy="locations")
	 */
	protected $marketings;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Rental\Rental", inversedBy="locations")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\User\User", inversedBy="locations")
	 */
	protected $users;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Country", mappedBy="location", cascade={"persist", "remove"})
	 */
	protected $country;

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


	/* ----------------------------- Nested Set Methods - DO NOT DELETE ----------------------------- */

	public function getLeftValue() { return $this->nestedLeft; }
	public function setLeftValue($nestedLeft) { $this->nestedLeft = $nestedLeft; }

	public function getRightValue() { return $this->nestedRight; }
	public function setRightValue($nestedRight) { $this->nestedRight = $nestedRight; }

	public function getRootValue() { return $this->nestedRoot; }
	public function setRootValue($nestedRoot) { $this->nestedRoot = $nestedRoot; }

	public function __toString() { return (string)$this->slug; }


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
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Location\Location
	 */
	public function setName(\Entities\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Location\Location
	 */
	public function setNameOfficial(\Entities\Dictionary\Phrase $nameOfficial) {
		$this->nameOfficial = $nameOfficial;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Phrase
	 */
	public function getNameOfficial() {
		return $this->nameOfficial;
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Location\Location
	 */
	public function setNameShort(\Entities\Dictionary\Phrase $nameShort) {
		$this->nameShort = $nameShort;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Phrase
	 */
	public function getNameShort() {
		return $this->nameShort;
	}

	/**
	 * @param slug
	 * @return \Entities\Location\Location
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
	 * @return \Entities\Location\Location
	 */
	public function setParentId($parentId) {
		$this->parentId = $parentId;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
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
	 * @param integer
	 * @return \Entities\Location\Location
	 */
	public function setNestedLeft($nestedLeft) {
		$this->nestedLeft = $nestedLeft;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
	 */
	public function unsetNestedLeft() {
		$this->nestedLeft = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getNestedLeft() {
		return $this->nestedLeft;
	}

	/**
	 * @param integer
	 * @return \Entities\Location\Location
	 */
	public function setNestedRight($nestedRight) {
		$this->nestedRight = $nestedRight;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
	 */
	public function unsetNestedRight() {
		$this->nestedRight = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getNestedRight() {
		return $this->nestedRight;
	}

	/**
	 * @param integer
	 * @return \Entities\Location\Location
	 */
	public function setNestedRoot($nestedRoot) {
		$this->nestedRoot = $nestedRoot;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
	 */
	public function unsetNestedRoot() {
		$this->nestedRoot = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getNestedRoot() {
		return $this->nestedRoot;
	}

	/**
	 * @param \Entities\Location\Type
	 * @return \Entities\Location\Location
	 */
	public function setType(\Entities\Location\Type $type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Location\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param json
	 * @return \Entities\Location\Location
	 */
	public function setPolygon($polygon) {
		$this->polygon = $polygon;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
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
	 * @return \Entities\Location\Location
	 */
	public function setLatitude(\Extras\Types\Latlong $latitude) {
		$this->latitude = $latitude;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
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
	 * @return \Entities\Location\Location
	 */
	public function setLongitude(\Extras\Types\Latlong $longitude) {
		$this->longitude = $longitude;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
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
	 * @return \Entities\Location\Location
	 */
	public function setDefaultZoom($defaultZoom) {
		$this->defaultZoom = $defaultZoom;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location
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
	 * @param \Entities\Company\BankAccount
	 * @return \Entities\Location\Location
	 */
	public function addBankAccount(\Entities\Company\BankAccount $bankAccount) {
		if(!$this->bankAccounts->contains($bankAccount)) {
			$this->bankAccounts->add($bankAccount);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Company\BankAccount
	 */
	public function getBankAccounts() {
		return $this->bankAccounts;
	}

	/**
	 * @param \Entities\Company\Company
	 * @return \Entities\Location\Location
	 */
	public function addCompany(\Entities\Company\Company $company) {
		if(!$this->companies->contains($company)) {
			$this->companies->add($company);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Company\Company
	 */
	public function getCompanies() {
		return $this->companies;
	}

	/**
	 * @param \Entities\Company\Office
	 * @return \Entities\Location\Location
	 */
	public function addOffice(\Entities\Company\Office $office) {
		if(!$this->offices->contains($office)) {
			$this->offices->add($office);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Company\Office
	 */
	public function getOffices() {
		return $this->offices;
	}

	/**
	 * @param \Entities\Domain
	 * @return \Entities\Location\Location
	 */
	public function setDomain(\Entities\Domain $domain) {
		$this->domain = $domain;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Domain
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * @param \Entities\Invoicing\Marketing
	 * @return \Entities\Location\Location
	 */
	public function addMarketing(\Entities\Invoicing\Marketing $marketing) {
		if(!$this->marketings->contains($marketing)) {
			$this->marketings->add($marketing);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Invoicing\Marketing
	 */
	public function getMarketings() {
		return $this->marketings;
	}

	/**
	 * @param \Entities\Rental\Rental
	 * @return \Entities\Location\Location
	 */
	public function addRental(\Entities\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}

	/**
	 * @param \Entities\User\User
	 * @return \Entities\Location\Location
	 */
	public function addUser(\Entities\User\User $user) {
		if(!$this->users->contains($user)) {
			$this->users->add($user);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\User\User
	 */
	public function getUsers() {
		return $this->users;
	}

	/**
	 * @param \Entities\Location\Country
	 * @return \Entities\Location\Location
	 */
	public function setCountry(\Entities\Location\Country $country) {
		$this->country = $country;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @param \Entities\Location\Traveling
	 * @return \Entities\Location\Location
	 */
	public function addIncomingLocation(\Entities\Location\Traveling $incomingLocation) {
		if(!$this->incomingLocations->contains($incomingLocation)) {
			$this->incomingLocations->add($incomingLocation);
		}

		return $this;
	}

	/**
	 * @param \Entities\Location\Traveling
	 * @return \Entities\Location\Location
	 */
	public function removeIncomingLocation(\Entities\Location\Traveling $incomingLocation) {
		if($this->incomingLocations->contains($incomingLocation)) {
			$this->incomingLocations->removeElement($incomingLocation);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Traveling
	 */
	public function getIncomingLocations() {
		return $this->incomingLocations;
	}

	/**
	 * @param \Entities\Location\Traveling
	 * @return \Entities\Location\Location
	 */
	public function addOutgoingLocation(\Entities\Location\Traveling $outgoingLocation) {
		if(!$this->outgoingLocations->contains($outgoingLocation)) {
			$this->outgoingLocations->add($outgoingLocation);
		}

		return $this;
	}

	/**
	 * @param \Entities\Location\Traveling
	 * @return \Entities\Location\Location
	 */
	public function removeOutgoingLocation(\Entities\Location\Traveling $outgoingLocation) {
		if($this->outgoingLocations->contains($outgoingLocation)) {
			$this->outgoingLocations->removeElement($outgoingLocation);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Traveling
	 */
	public function getOutgoingLocations() {
		return $this->outgoingLocations;
	}

}