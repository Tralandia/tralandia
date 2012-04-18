<?php

namespace Entity\Rental;

use Entity\Contact;
use Entity\Dictionary;
use Entity\Invoicing;
use Entity\Location;
use Entity\Medium;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_rental")
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
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
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
	 * @ORM\Column(type="decimal")
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
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Contact", mappedBy="rentals")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Dictionary\Language", mappedBy="rentals")
	 */
	protected $languagesSpoken;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Amenity\Amenity", mappedBy="rentals")
	 */
	protected $amenities;

	/**
	 * @var time
	 * @ORM\Column(type="time")
	 */
	protected $checkIn;

	/**
	 * @var time
	 * @ORM\Column(type="time")
	 */
	protected $checkOut;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $capacityMin;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $capacityMax;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $pricelist;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $priceSeason;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $priceOffseason;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="rental")
	 */
	protected $media;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $calendar;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
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
		$this->contacts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->languagesSpoken = new \Doctrine\Common\Collections\ArrayCollection;
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
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Rental\Rental
	 */
	public function addContact(\Entity\Contact\Contact $contact) {
		if(!$this->contacts->contains($contact)) {
			$this->contacts->add($contact);
		}
		$contact->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Rental\Rental
	 */
	public function removeContact(\Entity\Contact\Contact $contact) {
		if($this->contacts->contains($contact)) {
			$this->contacts->removeElement($contact);
		}
		$contact->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Contact\Contact
	 */
	public function getContacts() {
		return $this->contacts;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Rental\Rental
	 */
	public function addLanguagesSpoken(\Entity\Dictionary\Language $languagesSpoken) {
		if(!$this->languagesSpoken->contains($languagesSpoken)) {
			$this->languagesSpoken->add($languagesSpoken);
		}
		$languagesSpoken->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Rental\Rental
	 */
	public function removeLanguagesSpoken(\Entity\Dictionary\Language $languagesSpoken) {
		if($this->languagesSpoken->contains($languagesSpoken)) {
			$this->languagesSpoken->removeElement($languagesSpoken);
		}
		$languagesSpoken->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Dictionary\Language
	 */
	public function getLanguagesSpoken() {
		return $this->languagesSpoken;
	}
		
	/**
	 * @param \Entity\Rental\Amenity\Amenity
	 * @return \Entity\Rental\Rental
	 */
	public function addAmenity(\Entity\Rental\Amenity\Amenity $amenity) {
		if(!$this->amenities->contains($amenity)) {
			$this->amenities->add($amenity);
		}
		$amenity->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Amenity\Amenity
	 * @return \Entity\Rental\Rental
	 */
	public function removeAmenity(\Entity\Rental\Amenity\Amenity $amenity) {
		if($this->amenities->contains($amenity)) {
			$this->amenities->removeElement($amenity);
		}
		$amenity->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Amenity\Amenity
	 */
	public function getAmenities() {
		return $this->amenities;
	}
		
	/**
	 * @param \Extras\Types\Time
	 * @return \Entity\Rental\Rental
	 */
	public function setCheckIn(\Extras\Types\Time $checkIn) {
		$this->checkIn = $checkIn;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Time|NULL
	 */
	public function getCheckIn() {
		return $this->checkIn;
	}
		
	/**
	 * @param \Extras\Types\Time
	 * @return \Entity\Rental\Rental
	 */
	public function setCheckOut(\Extras\Types\Time $checkOut) {
		$this->checkOut = $checkOut;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Time|NULL
	 */
	public function getCheckOut() {
		return $this->checkOut;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Rental\Rental
	 */
	public function setCapacityMin($capacityMin) {
		$this->capacityMin = $capacityMin;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCapacityMin() {
		return $this->capacityMin;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Rental\Rental
	 */
	public function setCapacityMax($capacityMax) {
		$this->capacityMax = $capacityMax;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCapacityMax() {
		return $this->capacityMax;
	}
		
	/**
	 * @param json
	 * @return \Entity\Rental\Rental
	 */
	public function setPricelist($pricelist) {
		$this->pricelist = $pricelist;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPricelist() {
		return $this->pricelist;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Rental\Rental
	 */
	public function setPriceSeason(\Extras\Types\Price $priceSeason) {
		$this->priceSeason = $priceSeason;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getPriceSeason() {
		return $this->priceSeason;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Rental\Rental
	 */
	public function setPriceOffseason(\Extras\Types\Price $priceOffseason) {
		$this->priceOffseason = $priceOffseason;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getPriceOffseason() {
		return $this->priceOffseason;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Rental\Rental
	 */
	public function addMedia(\Entity\Medium\Medium $media) {
		if(!$this->media->contains($media)) {
			$this->media->add($media);
		}
		$media->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Rental\Rental
	 */
	public function removeMedia(\Entity\Medium\Medium $media) {
		if($this->media->contains($media)) {
			$this->media->removeElement($media);
		}
		$media->unsetRental();

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