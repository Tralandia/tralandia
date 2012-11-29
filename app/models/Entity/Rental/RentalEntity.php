<?php

namespace Entity\Rental;

use Entity\Phrase;
use Entity\Invoice;
use Entity\Location;
use Entity\Medium;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental", indexes={@ORM\index(name="status", columns={"status"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="calendarUpdated", columns={"calendarUpdated"})})
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
	 * @ORM\ManyToOne(targetEntity="Entity\Language", cascade={"persist"})
	 */
	protected $editLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status = self::STATUS_DRAFT;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type", inversedBy="rentals")
	 */
	protected $type;


	/**
	 * @var decimal
	 * @ORM\Column(type="decimal", nullable=true)
	 */
	protected $rank;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Information", mappedBy="rentals")
	 */
	protected $missingInformation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location", inversedBy="rental")
	 */
	protected $primaryLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="rentals")
	 */
	protected $locations;

	/**
	 * @var address
	 * @ORM\Column(type="address", nullable=true)
	 */
	protected $address;

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
	 * @var slug
	 * @ORM\Column(type="slug", nullable=true)
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $interview;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $teaser;

	/**
	 * @var contacts
	 * @ORM\Column(type="contacts", nullable=true)
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Language", mappedBy="rentals", cascade={"persist"})
	 */
	protected $spokenLanguages;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Amenity", mappedBy="rentals")
	 */
	protected $amenities;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Tag", mappedBy="rentals")
	 */
	protected $tags;

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
	 * @ORM\Column(type="json", nullable=true)
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
	 * @ORM\OneToMany(targetEntity="Entity\Invoice\Invoice", mappedBy="rental")
	 */
	protected $invoices;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Seo\BackLink", mappedBy="rental")
	 */
	protected $backLinks;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $maxCapacity;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $rooms;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->missingInformation = new \Doctrine\Common\Collections\ArrayCollection;
		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->spokenLanguages = new \Doctrine\Common\Collections\ArrayCollection;
		$this->amenities = new \Doctrine\Common\Collections\ArrayCollection;
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection;
		$this->media = new \Doctrine\Common\Collections\ArrayCollection;
		$this->fulltexts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->invoices = new \Doctrine\Common\Collections\ArrayCollection;
		$this->backLinks = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Rental\Rental
	 */
	public function setUser(\Entity\User\User $user)
	{
		$this->user = $user;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetUser()
	{
		$this->user = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getUser()
	{
		return $this->user;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Rental\Rental
	 */
	public function setEditLanguage(\Entity\Language $editLanguage)
	{
		$this->editLanguage = $editLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetEditLanguage()
	{
		$this->editLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getEditLanguage()
	{
		return $this->editLanguage;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Rental\Rental
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getStatus()
	{
		return $this->status;
	}
		
	/**
	 * @param \Entity\Rental\Type
	 * @return \Entity\Rental\Rental
	 */
	public function setType(\Entity\Rental\Type $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Type|NULL
	 */
	public function getType()
	{
		return $this->type;
	}
		
	/**
	 * @param decimal
	 * @return \Entity\Rental\Rental
	 */
	public function setRank($rank)
	{
		$this->rank = $rank;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetRank()
	{
		$this->rank = NULL;

		return $this;
	}
		
	/**
	 * @return decimal|NULL
	 */
	public function getRank()
	{
		return $this->rank;
	}
		
	/**
	 * @param \Entity\Rental\Information
	 * @return \Entity\Rental\Rental
	 */
	public function addMissingInformation(\Entity\Rental\Information $missingInformation)
	{
		if(!$this->missingInformation->contains($missingInformation)) {
			$this->missingInformation->add($missingInformation);
		}
		$missingInformation->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Information
	 * @return \Entity\Rental\Rental
	 */
	public function removeMissingInformation(\Entity\Rental\Information $missingInformation)
	{
		if($this->missingInformation->contains($missingInformation)) {
			$this->missingInformation->removeElement($missingInformation);
		}
		$missingInformation->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Information
	 */
	public function getMissingInformation()
	{
		return $this->missingInformation;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Rental\Rental
	 */
	public function addLocation(\Entity\Location\Location $location)
	{
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
	public function removeLocation(\Entity\Location\Location $location)
	{
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->removeRental($this);

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
	 * @param \Extras\Types\Address
	 * @return \Entity\Rental\Rental
	 */
	public function setAddress(\Extras\Types\Address $address)
	{
		$this->address = $address;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetAddress()
	{
		$this->address = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Address|NULL
	 */
	public function getAddress()
	{
		return $this->address;
	}
		
	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Rental\Rental
	 */
	public function setLatitude(\Extras\Types\Latlong $latitude)
	{
		$this->latitude = $latitude;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
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
	 * @return \Entity\Rental\Rental
	 */
	public function setLongitude(\Extras\Types\Latlong $longitude)
	{
		$this->longitude = $longitude;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
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
	 * @param slug
	 * @return \Entity\Rental\Rental
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetSlug()
	{
		$this->slug = NULL;

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
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\Rental
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
	 * @param json
	 * @return \Entity\Rental\Rental
	 */
	public function setInterview($interview)
	{
		$this->interview = $interview;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetInterview()
	{
		$this->interview = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getInterview()
	{
		return $this->interview;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\Rental
	 */
	public function setTeaser(\Entity\Phrase\Phrase $teaser)
	{
		$this->teaser = $teaser;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getTeaser()
	{
		return $this->teaser;
	}
		
	/**
	 * @param \Extras\Types\Contacts
	 * @return \Entity\Rental\Rental
	 */
	public function setContacts(\Extras\Types\Contacts $contacts)
	{
		$this->contacts = $contacts;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
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
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Rental\Rental
	 */
	public function addSpokenLanguage(\Entity\Language $spokenLanguage)
	{
		if(!$this->spokenLanguages->contains($spokenLanguage)) {
			$this->spokenLanguages->add($spokenLanguage);
		}
		$spokenLanguage->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Rental\Rental
	 */
	public function removeSpokenLanguage(\Entity\Language $spokenLanguage)
	{
		if($this->spokenLanguages->contains($spokenLanguage)) {
			$this->spokenLanguages->removeElement($spokenLanguage);
		}
		$spokenLanguage->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Language
	 */
	public function getSpokenLanguages()
	{
		return $this->spokenLanguages;
	}
		
	/**
	 * @param \Entity\Rental\Amenity
	 * @return \Entity\Rental\Rental
	 */
	public function addAmenity(\Entity\Rental\Amenity $amenity)
	{
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
	public function removeAmenity(\Entity\Rental\Amenity $amenity)
	{
		if($this->amenities->contains($amenity)) {
			$this->amenities->removeElement($amenity);
		}
		$amenity->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Amenity
	 */
	public function getAmenities()
	{
		return $this->amenities;
	}
		
	/**
	 * @param \Entity\Rental\Tag
	 * @return \Entity\Rental\Rental
	 */
	public function addTag(\Entity\Rental\Tag $tag)
	{
		if(!$this->tags->contains($tag)) {
			$this->tags->add($tag);
		}
		$tag->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Tag
	 * @return \Entity\Rental\Rental
	 */
	public function removeTag(\Entity\Rental\Tag $tag)
	{
		if($this->tags->contains($tag)) {
			$this->tags->removeElement($tag);
		}
		$tag->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Tag
	 */
	public function getTags()
	{
		return $this->tags;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setCheckIn($checkIn)
	{
		$this->checkIn = $checkIn;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetCheckIn()
	{
		$this->checkIn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCheckIn()
	{
		return $this->checkIn;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setCheckOut($checkOut)
	{
		$this->checkOut = $checkOut;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetCheckOut()
	{
		$this->checkOut = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCheckOut()
	{
		return $this->checkOut;
	}
		
	/**
	 * @param json
	 * @return \Entity\Rental\Rental
	 */
	public function setPricelists($pricelists)
	{
		$this->pricelists = $pricelists;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetPricelists()
	{
		$this->pricelists = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPricelists()
	{
		return $this->pricelists;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Rental\Rental
	 */
	public function addMedium(\Entity\Medium\Medium $medium)
	{
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
	public function removeMedium(\Entity\Medium\Medium $medium)
	{
		if($this->media->contains($medium)) {
			$this->media->removeElement($medium);
		}
		$medium->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Medium\Medium
	 */
	public function getMedia()
	{
		return $this->media;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setCalendar($calendar)
	{
		$this->calendar = $calendar;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetCalendar()
	{
		$this->calendar = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCalendar()
	{
		return $this->calendar;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Rental\Rental
	 */
	public function setCalendarUpdated(\DateTime $calendarUpdated)
	{
		$this->calendarUpdated = $calendarUpdated;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetCalendarUpdated()
	{
		$this->calendarUpdated = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getCalendarUpdated()
	{
		return $this->calendarUpdated;
	}
		
	/**
	 * @param \Entity\Rental\Fulltext
	 * @return \Entity\Rental\Rental
	 */
	public function addFulltext(\Entity\Rental\Fulltext $fulltext)
	{
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
	public function removeFulltext(\Entity\Rental\Fulltext $fulltext)
	{
		if($this->fulltexts->contains($fulltext)) {
			$this->fulltexts->removeElement($fulltext);
		}
		$fulltext->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Fulltext
	 */
	public function getFulltexts()
	{
		return $this->fulltexts;
	}
		
	/**
	 * @param \Entity\Invoice\Invoice
	 * @return \Entity\Rental\Rental
	 */
	public function addInvoice(\Entity\Invoice\Invoice $invoice)
	{
		if(!$this->invoices->contains($invoice)) {
			$this->invoices->add($invoice);
		}
		$invoice->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoice\Invoice
	 * @return \Entity\Rental\Rental
	 */
	public function removeInvoice(\Entity\Invoice\Invoice $invoice)
	{
		if($this->invoices->contains($invoice)) {
			$this->invoices->removeElement($invoice);
		}
		$invoice->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoice\Invoice
	 */
	public function getInvoices()
	{
		return $this->invoices;
	}
		
	/**
	 * @param \Entity\Seo\BackLink
	 * @return \Entity\Rental\Rental
	 */
	public function addBackLink(\Entity\Seo\BackLink $backLink)
	{
		if(!$this->backLinks->contains($backLink)) {
			$this->backLinks->add($backLink);
		}
		$backLink->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Seo\BackLink
	 * @return \Entity\Rental\Rental
	 */
	public function removeBackLink(\Entity\Seo\BackLink $backLink)
	{
		if($this->backLinks->contains($backLink)) {
			$this->backLinks->removeElement($backLink);
		}
		$backLink->unsetRental();

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
	 * @param integer
	 * @return \Entity\Rental\Rental
	 */
	public function setMaxCapacity($maxCapacity)
	{
		$this->maxCapacity = $maxCapacity;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetMaxCapacity()
	{
		$this->maxCapacity = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getMaxCapacity()
	{
		return $this->maxCapacity;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setRooms($rooms)
	{
		$this->rooms = $rooms;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetRooms()
	{
		$this->rooms = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getRooms()
	{
		return $this->rooms;
	}
}