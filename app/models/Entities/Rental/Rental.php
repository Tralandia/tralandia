<?php

namespace Entities\Rental;

use Entities\Contact;
use Entities\Dictionary;
use Entities\Invoicing;
use Entities\Location;
use Entities\Medium;
use Entities\Rental;
use Entities\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_rental")
 */
class Rental extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="User\User")
	 */
	protected $owner;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $editLanguage;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $status;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Type")
	 */
	protected $types;

	/**
	 * @var datetime
	 * @ORM\ManyToMany(type="datetime")
	 */
	protected $timeDeleted;

	/**
	 * @var decimal
	 * @ORM\ManyToMany(type="decimal")
	 */
	protected $rank;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Location\Location")
	 */
	protected $locations;

	/**
	 * @var address
	 * @ORM\ManyToMany(type="address")
	 */
	protected $address;

	/**
	 * @var latlong
	 * @ORM\ManyToMany(type="latlong")
	 */
	protected $latitude;

	/**
	 * @var latlong
	 * @ORM\ManyToMany(type="latlong")
	 */
	protected $longitude;

	/**
	 * @var webalizedString
	 * @ORM\ManyToMany(type="webalizedString")
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $briefDescription;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $teaser;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $languagesSpoken;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Rental\Amenity\Amenity")
	 */
	protected $amenities;

	/**
	 * @var time
	 * @ORM\ManyToMany(type="time")
	 */
	protected $checkIn;

	/**
	 * @var time
	 * @ORM\ManyToMany(type="time")
	 */
	protected $checkOut;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $capacityMin;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $capacityMax;

	/**
	 * @var json
	 * @ORM\ManyToMany(type="json")
	 */
	protected $pricelist;

	/**
	 * @var price
	 * @ORM\ManyToMany(type="price")
	 */
	protected $priceSeason;

	/**
	 * @var price
	 * @ORM\ManyToMany(type="price")
	 */
	protected $priceOffseason;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Medium\Medium")
	 */
	protected $media;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $calendar;

	/**
	 * @var datetime
	 * @ORM\ManyToMany(type="datetime")
	 */
	protected $calendarUpdated;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Fulltext")
	 */
	protected $fulltexts;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Invoicing\Invoice", mappedBy="rental")
	 */
	protected $invoices;


	public function __construct() {
		$this->invoices = new ArrayCollection();
	}


	/**
	 * @param User\User $owner
	 * @return Rental
	 */
	public function setOwner(User\User  $owner) {
		$this->owner = $owner;
		return $this;
	}


	/**
	 * @return User\User
	 */
	public function getOwner() {
		return $this->owner;
	}


	/**
	 * @param Dictionary\Language $editLanguage
	 * @return Rental
	 */
	public function setEditLanguage(Dictionary\Language  $editLanguage) {
		$this->editLanguage = $editLanguage;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getEditLanguage() {
		return $this->editLanguage;
	}


	/**
	 * @param integer $status
	 * @return Rental
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * @param Type $types
	 * @return Rental
	 */
	public function setTypes(Type  $types) {
		$this->types = $types;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getTypes() {
		return $this->types;
	}


	/**
	 * @param datetime $timeDeleted
	 * @return Rental
	 */
	public function setTimeDeleted($timeDeleted) {
		$this->timeDeleted = $timeDeleted;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getTimeDeleted() {
		return $this->timeDeleted;
	}


	/**
	 * @param decimal $rank
	 * @return Rental
	 */
	public function setRank($rank) {
		$this->rank = $rank;
		return $this;
	}


	/**
	 * @return decimal
	 */
	public function getRank() {
		return $this->rank;
	}


	/**
	 * @param Location\Location $locations
	 * @return Rental
	 */
	public function setLocations(Location\Location  $locations) {
		$this->locations = $locations;
		return $this;
	}


	/**
	 * @return Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}


	/**
	 * @param address $address
	 * @return Rental
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}


	/**
	 * @return address
	 */
	public function getAddress() {
		return $this->address;
	}


	/**
	 * @param latlong $latitude
	 * @return Rental
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
		return $this;
	}


	/**
	 * @return latlong
	 */
	public function getLatitude() {
		return $this->latitude;
	}


	/**
	 * @param latlong $longitude
	 * @return Rental
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
		return $this;
	}


	/**
	 * @return latlong
	 */
	public function getLongitude() {
		return $this->longitude;
	}


	/**
	 * @param webalizedString $slug
	 * @return Rental
	 */
	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
	}


	/**
	 * @return webalizedString
	 */
	public function getSlug() {
		return $this->slug;
	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Rental
	 */
	public function setName(Dictionary\Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param Dictionary\Phrase $briefDescription
	 * @return Rental
	 */
	public function setBriefDescription(Dictionary\Phrase  $briefDescription) {
		$this->briefDescription = $briefDescription;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getBriefDescription() {
		return $this->briefDescription;
	}


	/**
	 * @param Dictionary\Phrase $description
	 * @return Rental
	 */
	public function setDescription(Dictionary\Phrase  $description) {
		$this->description = $description;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param Dictionary\Phrase $teaser
	 * @return Rental
	 */
	public function setTeaser(Dictionary\Phrase  $teaser) {
		$this->teaser = $teaser;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getTeaser() {
		return $this->teaser;
	}


	/**
	 * @param Contact\Contact $contacts
	 * @return Rental
	 */
	public function setContacts(Contact\Contact  $contacts) {
		$this->contacts = $contacts;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getContacts() {
		return $this->contacts;
	}


	/**
	 * @param Dictionary\Language $languagesSpoken
	 * @return Rental
	 */
	public function setLanguagesSpoken(Dictionary\Language  $languagesSpoken) {
		$this->languagesSpoken = $languagesSpoken;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguagesSpoken() {
		return $this->languagesSpoken;
	}


	/**
	 * @param Rental\Amenity\Amenity $amenities
	 * @return Rental
	 */
	public function setAmenities(Rental\Amenity\Amenity  $amenities) {
		$this->amenities = $amenities;
		return $this;
	}


	/**
	 * @return Rental\Amenity\Amenity
	 */
	public function getAmenities() {
		return $this->amenities;
	}


	/**
	 * @param time $checkIn
	 * @return Rental
	 */
	public function setCheckIn($checkIn) {
		$this->checkIn = $checkIn;
		return $this;
	}


	/**
	 * @return time
	 */
	public function getCheckIn() {
		return $this->checkIn;
	}


	/**
	 * @param time $checkOut
	 * @return Rental
	 */
	public function setCheckOut($checkOut) {
		$this->checkOut = $checkOut;
		return $this;
	}


	/**
	 * @return time
	 */
	public function getCheckOut() {
		return $this->checkOut;
	}


	/**
	 * @param integer $capacityMin
	 * @return Rental
	 */
	public function setCapacityMin($capacityMin) {
		$this->capacityMin = $capacityMin;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getCapacityMin() {
		return $this->capacityMin;
	}


	/**
	 * @param integer $capacityMax
	 * @return Rental
	 */
	public function setCapacityMax($capacityMax) {
		$this->capacityMax = $capacityMax;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getCapacityMax() {
		return $this->capacityMax;
	}


	/**
	 * @param json $pricelist
	 * @return Rental
	 */
	public function setPricelist($pricelist) {
		$this->pricelist = $pricelist;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getPricelist() {
		return $this->pricelist;
	}


	/**
	 * @param price $priceSeason
	 * @return Rental
	 */
	public function setPriceSeason($priceSeason) {
		$this->priceSeason = $priceSeason;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getPriceSeason() {
		return $this->priceSeason;
	}


	/**
	 * @param price $priceOffseason
	 * @return Rental
	 */
	public function setPriceOffseason($priceOffseason) {
		$this->priceOffseason = $priceOffseason;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getPriceOffseason() {
		return $this->priceOffseason;
	}


	/**
	 * @param Medium\Medium $media
	 * @return Rental
	 */
	public function setMedia(Medium\Medium  $media) {
		$this->media = $media;
		return $this;
	}


	/**
	 * @return Medium\Medium
	 */
	public function getMedia() {
		return $this->media;
	}


	/**
	 * @param text $calendar
	 * @return Rental
	 */
	public function setCalendar($calendar) {
		$this->calendar = $calendar;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getCalendar() {
		return $this->calendar;
	}


	/**
	 * @param datetime $calendarUpdated
	 * @return Rental
	 */
	public function setCalendarUpdated($calendarUpdated) {
		$this->calendarUpdated = $calendarUpdated;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getCalendarUpdated() {
		return $this->calendarUpdated;
	}


	/**
	 * @param Fulltext $fulltexts
	 * @return Rental
	 */
	public function setFulltexts(Fulltext  $fulltexts) {
		$this->fulltexts = $fulltexts;
		return $this;
	}


	/**
	 * @return Fulltext
	 */
	public function getFulltexts() {
		return $this->fulltexts;
	}


	/**
	 * @param Invoicing\Invoice $invoice
	 * @return Rental
	 */
	public function addInvoice(Invoicing\Invoice  $invoice) {
		$this->invoices->add($invoice);
		return $this;
	}


	/**
	 * @param Invoicing\Invoice $invoice
	 * @return Rental
	 */
	public function removeInvoice(Invoicing\Invoice  $invoice) {
		$this->invoices->removeElement($invoice);
		return $this;
	}


	/**
	 * @return Invoicing\Invoice[]
	 */
	public function getInvoice() {
		return $this->invoices->toArray();
	}

}
