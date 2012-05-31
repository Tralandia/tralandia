<?php

namespace Entity\Rental;

use Entity\Dictionary;
use Entity\Invoicing;
use Entity\Location;
use Entity\Medium;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_rental", indexes={@ORM\index(name="status", columns={"status"}), @ORM\index(name="timeDeleted", columns={"timeDeleted"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="calendarUpdated", columns={"calendarUpdated"})})
 * @EA\Service(name="\Service\Rental\Rental")
 * @EA\ServiceList(name="\Service\Rental\RentalList")
 * @EA\Primary(key="id", value="slug")
 */
class Rental extends \Entity\BaseEntity {

	const STATUS_DRAFT = 0;
	const STATUS_CHECKED = 3;
	const STATUS_LIVE = 6;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User", inversedBy="rentals")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language", cascade={"persist"})
	 */
	protected $editLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Type", mappedBy="rentals")
	 */
	protected $types;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $timeDeleted;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal", nullable=true)
	 */
	protected $rank;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="rentals")
	 */
	protected $locations;

	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $address;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong")
	 */
	protected $latitude;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong")
	 */
	protected $longitude;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $briefDescription;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $teaser;

	/**
	 * @var contacts
	 * @ORM\Column(type="contacts", nullable=true)
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Dictionary\Language", mappedBy="rentals", cascade={"persist"})
	 */
	protected $spokenLanguages;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Amenity", mappedBy="rentals")
	 */
	protected $amenities;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $checkIn;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $checkOut;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $pricelists;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="rental", cascade={"persist"})
	 */
	protected $media;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $calendar;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $calendarUpdated;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Fulltext", mappedBy="rental")
	 */
	protected $fulltexts;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Invoicing\Invoice", mappedBy="rental")
	 */
	protected $invoices;


















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->types = new \Doctrine\Common\Collections\ArrayCollection;
		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->spokenLanguages = new \Doctrine\Common\Collections\ArrayCollection;
		$this->amenities = new \Doctrine\Common\Collections\ArrayCollection;
		$this->media = new \Doctrine\Common\Collections\ArrayCollection;
		$this->fulltexts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->invoices = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Rental\Rental
	 */
	public function setUser(\Entity\User\User $user) {
		$this->user = $user;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetUser() {
		$this->user = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getUser() {
		return $this->user;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Rental\Rental
	 */
	public function setEditLanguage(\Entity\Dictionary\Language $editLanguage) {
		$this->editLanguage = $editLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetEditLanguage() {
		$this->editLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getEditLanguage() {
		return $this->editLanguage;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Rental\Rental
	 */
	public function setStatus($status) {
		$this->status = $status;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getStatus() {
		return $this->status;
	}
		
	/**
	 * @param \Entity\Rental\Type
	 * @return \Entity\Rental\Rental
	 */
	public function addType(\Entity\Rental\Type $type) {
		if(!$this->types->contains($type)) {
			$this->types->add($type);
		}
		$type->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Type
	 * @return \Entity\Rental\Rental
	 */
	public function removeType(\Entity\Rental\Type $type) {
		if($this->types->contains($type)) {
			$this->types->removeElement($type);
		}
		$type->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Type
	 */
	public function getTypes() {
		return $this->types;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Rental\Rental
	 */
	public function setTimeDeleted(\DateTime $timeDeleted) {
		$this->timeDeleted = $timeDeleted;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getTimeDeleted() {
		return $this->timeDeleted;
	}
		
	/**
	 * @param decimal
	 * @return \Entity\Rental\Rental
	 */
	public function setRank($rank) {
		$this->rank = $rank;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetRank() {
		$this->rank = NULL;

		return $this;
	}
		
	/**
	 * @return decimal|NULL
	 */
	public function getRank() {
		return $this->rank;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Rental\Rental
	 */
	public function addLocation(\Entity\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Rental\Rental
	 */
	public function removeLocation(\Entity\Location\Location $location) {
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}
		
	/**
	 * @param \Extras\Types\Address
	 * @return \Entity\Rental\Rental
	 */
	public function setAddress(\Extras\Types\Address $address) {
		$this->address = $address;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Address|NULL
	 */
	public function getAddress() {
		return $this->address;
	}
		
	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Rental\Rental
	 */
	public function setLatitude(\Extras\Types\Latlong $latitude) {
		$this->latitude = $latitude;

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
	 * @return \Entity\Rental\Rental
	 */
	public function setLongitude(\Extras\Types\Latlong $longitude) {
		$this->longitude = $longitude;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Latlong|NULL
	 */
	public function getLongitude() {
		return $this->longitude;
	}
		
	/**
	 * @param slug
	 * @return \Entity\Rental\Rental
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
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Rental\Rental
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
	 * @return \Entity\Rental\Rental
	 */
	public function setBriefDescription(\Entity\Dictionary\Phrase $briefDescription) {
		$this->briefDescription = $briefDescription;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getBriefDescription() {
		return $this->briefDescription;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Rental\Rental
	 */
	public function setDescription(\Entity\Dictionary\Phrase $description) {
		$this->description = $description;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getDescription() {
		return $this->description;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Rental\Rental
	 */
	public function setTeaser(\Entity\Dictionary\Phrase $teaser) {
		$this->teaser = $teaser;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getTeaser() {
		return $this->teaser;
	}
		
	/**
	 * @param \Extras\Types\Contacts
	 * @return \Entity\Rental\Rental
	 */
	public function setContacts(\Extras\Types\Contacts $contacts) {
		$this->contacts = $contacts;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetContacts() {
		$this->contacts = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Contacts|NULL
	 */
	public function getContacts() {
		return $this->contacts;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Rental\Rental
	 */
	public function addSpokenLanguage(\Entity\Dictionary\Language $spokenLanguage) {
		if(!$this->spokenLanguages->contains($spokenLanguage)) {
			$this->spokenLanguages->add($spokenLanguage);
		}
		$spokenLanguage->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Rental\Rental
	 */
	public function removeSpokenLanguage(\Entity\Dictionary\Language $spokenLanguage) {
		if($this->spokenLanguages->contains($spokenLanguage)) {
			$this->spokenLanguages->removeElement($spokenLanguage);
		}
		$spokenLanguage->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Dictionary\Language
	 */
	public function getSpokenLanguages() {
		return $this->spokenLanguages;
	}
		
	/**
	 * @param \Entity\Rental\Amenity
	 * @return \Entity\Rental\Rental
	 */
	public function addAmenity(\Entity\Rental\Amenity $amenity) {
		if(!$this->amenities->contains($amenity)) {
			$this->amenities->add($amenity);
		}
		$amenity->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Amenity
	 * @return \Entity\Rental\Rental
	 */
	public function removeAmenity(\Entity\Rental\Amenity $amenity) {
		if($this->amenities->contains($amenity)) {
			$this->amenities->removeElement($amenity);
		}
		$amenity->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Amenity
	 */
	public function getAmenities() {
		return $this->amenities;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setCheckIn($checkIn) {
		$this->checkIn = $checkIn;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetCheckIn() {
		$this->checkIn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCheckIn() {
		return $this->checkIn;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setCheckOut($checkOut) {
		$this->checkOut = $checkOut;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetCheckOut() {
		$this->checkOut = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCheckOut() {
		return $this->checkOut;
	}
		
	/**
	 * @param json
	 * @return \Entity\Rental\Rental
	 */
	public function setPricelists($pricelists) {
		$this->pricelists = $pricelists;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPricelists() {
		return $this->pricelists;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Rental\Rental
	 */
	public function addMedium(\Entity\Medium\Medium $medium) {
		if(!$this->media->contains($medium)) {
			$this->media->add($medium);
		}
		$medium->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Rental\Rental
	 */
	public function removeMedium(\Entity\Medium\Medium $medium) {
		if($this->media->contains($medium)) {
			$this->media->removeElement($medium);
		}
		$medium->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Medium\Medium
	 */
	public function getMedia() {
		return $this->media;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setCalendar($calendar) {
		$this->calendar = $calendar;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetCalendar() {
		$this->calendar = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCalendar() {
		return $this->calendar;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Rental\Rental
	 */
	public function setCalendarUpdated(\DateTime $calendarUpdated) {
		$this->calendarUpdated = $calendarUpdated;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetCalendarUpdated() {
		$this->calendarUpdated = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getCalendarUpdated() {
		return $this->calendarUpdated;
	}
		
	/**
	 * @param \Entity\Rental\Fulltext
	 * @return \Entity\Rental\Rental
	 */
	public function addFulltext(\Entity\Rental\Fulltext $fulltext) {
		if(!$this->fulltexts->contains($fulltext)) {
			$this->fulltexts->add($fulltext);
		}
		$fulltext->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Fulltext
	 * @return \Entity\Rental\Rental
	 */
	public function removeFulltext(\Entity\Rental\Fulltext $fulltext) {
		if($this->fulltexts->contains($fulltext)) {
			$this->fulltexts->removeElement($fulltext);
		}
		$fulltext->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Fulltext
	 */
	public function getFulltexts() {
		return $this->fulltexts;
	}
		
	/**
	 * @param \Entity\Invoicing\Invoice
	 * @return \Entity\Rental\Rental
	 */
	public function addInvoice(\Entity\Invoicing\Invoice $invoice) {
		if(!$this->invoices->contains($invoice)) {
			$this->invoices->add($invoice);
		}
		$invoice->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoicing\Invoice
	 * @return \Entity\Rental\Rental
	 */
	public function removeInvoice(\Entity\Invoicing\Invoice $invoice) {
		if($this->invoices->contains($invoice)) {
			$this->invoices->removeElement($invoice);
		}
		$invoice->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoicing\Invoice
	 */
	public function getInvoices() {
		return $this->invoices;
	}
}