<?php

namespace Entity\Rental;

use Entity\Phrase;
use Entity\Location;
use Entity\Medium;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Rental\RentalRepository")
 * @ORM\Table(name="rental", indexes={@ORM\index(name="status", columns={"status"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="calendarUpdated", columns={"calendarUpdated"})})
 * @EA\Primary(key="id", value="slug")
 * @EA\Generator(skip="{getImages,getPrice,setPrice,setSlug}")
 */
class Rental extends \Entity\BaseEntity implements \Security\IOwnerable {

	const STATUS_DRAFT = 0;
	const STATUS_LIVE = 6;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User", inversedBy="rentals", cascade={"persist"})
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
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $classification;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $rank;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Information", mappedBy="rentals", cascade={"persist"})
	 */
	protected $missingInformation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Contact\Address", cascade={"persist", "remove"})
	 */
	protected $address;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $teaser;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $contactName;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Phone", mappedBy="rentals", cascade={"persist"})
	 */
	protected $phones;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Email", mappedBy="rentals", cascade={"persist"})
	 */
	protected $emails;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Url", mappedBy="rentals", cascade={"persist"})
	 */
	protected $urls;

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
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $checkIn;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $checkOut;

	/**
	 * @var Boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $pricesUponRequest = FALSE;

	/**
	 * @var price
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $price;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Image", mappedBy="rental", cascade={"persist"})
	 */
	protected $images;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="PricelistRow", mappedBy="rental", cascade={"persist", "remove"})
	 */
	protected $pricelistRows;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Pricelist", mappedBy="rental", cascade={"persist", "remove"})
	 */
	protected $pricelists;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="InterviewAnswer", mappedBy="rental", cascade={"persist"})
	 */
	protected $interviewAnswers;

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
	 * @ORM\OneToMany(targetEntity="Entity\Seo\BackLink", mappedBy="rental")
	 */
	protected $backLinks;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $maxCapacity;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $bedroomCount;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $rooms;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Rental\Service", mappedBy="rental")
	 */
	protected $services;

	public function getOwnerId()
	{
		return $this->getUser()->getId();
	}

	public function getMainImage() {
		$t = $this->images->first();
		if (!$t) {
			$t = 'http://www.tralandia.sk/u/87/13376844217106.jpg';
		}
		return $t;
	}

	public function getImages($limit = NULL, $offset = 0) {
		$images = $this->images->slice($offset, $limit);
		if (count($images) == 0) {
			$images = array(
				'http://www.tralandia.sk/u/87/13376844217106.jpg',		
				'http://www.tralandia.sk/u/87/13376844369216.jpg',		
				'http://www.tralandia.sk/u/87/13376844270394.jpg',		
				'http://www.tralandia.sk/u/87/13376844324019.jpg',		
			);
			return array_slice($images, 0, $limit);
		}
		return $images;
	}

	public function getAmenitiesByType($types, $limit = NULL)
	{
		$returnJustOneType = NULL;
		if(!is_array($types)) {
			$returnJustOneType = $types;
			$types = array($types);
		}

		$return = array();
		$i = 0;
		foreach ($this->getAmenities() as $amenity) {
			if(in_array($amenity->type->slug, $types)) {
				$return[$amenity->type->slug][] = $amenity;
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

	/**
	 * @return \Extras\Types\Price
	 */
	public function getPrice()
	{
		return new \Extras\Types\Price($this->price, $this->getCurrency());
	}

	public function setPrice(\Extras\Types\Price $price)
	{
		$this->price = $price->convertToFloat($this->getCurrency());

		return $this;
	}

	public function setFloatPrice($price)
	{
		$this->setPrice(new \Extras\Types\Price($price, $this->getCurrency()));
	}

	public function getCurrency() {
		return $this->getAddress()->getPrimaryLocation()->getDefaultCurrency();
	}

	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}		

	public function getCompulsoryMissingInformation()
	{
		return $this->missingInformation->filter(function($e){
			/** @var $e \Entity\Rental\Information */
			return $e->getCompulsory();
		});
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->missingInformation = new \Doctrine\Common\Collections\ArrayCollection;
		$this->phones = new \Doctrine\Common\Collections\ArrayCollection;
		$this->emails = new \Doctrine\Common\Collections\ArrayCollection;
		$this->urls = new \Doctrine\Common\Collections\ArrayCollection;
		$this->spokenLanguages = new \Doctrine\Common\Collections\ArrayCollection;
		$this->amenities = new \Doctrine\Common\Collections\ArrayCollection;
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection;
		$this->images = new \Doctrine\Common\Collections\ArrayCollection;
		$this->pricelistRows = new \Doctrine\Common\Collections\ArrayCollection;
		$this->pricelists = new \Doctrine\Common\Collections\ArrayCollection;
		$this->interviewAnswers = new \Doctrine\Common\Collections\ArrayCollection;
		$this->fulltexts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->backLinks = new \Doctrine\Common\Collections\ArrayCollection;
		$this->services = new \Doctrine\Common\Collections\ArrayCollection;
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
	 * @param float
	 * @return \Entity\Rental\Rental
	 */
	public function setClassification($classification)
	{
		$this->classification = $classification;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetClassification()
	{
		$this->classification = NULL;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getClassification()
	{
		return $this->classification;
	}
		
	/**
	 * @param integer
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
	 * @return integer|NULL
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
		$this->missingInformation->removeElement($missingInformation);
		$missingInformation->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Information[]
	 */
	public function getMissingInformation()
	{
		return $this->missingInformation;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Rental\Rental
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
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
	 * @param \Entity\Contact\Address
	 * @return \Entity\Rental\Rental
	 */
	public function setAddress(\Entity\Contact\Address $address)
	{
		$this->address = $address;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Address|NULL
	 */
	public function getAddress()
	{
		return $this->address;
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
	 * @return string|NULL
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
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setContactName($contactName)
	{
		$this->contactName = $contactName;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetContactName()
	{
		$this->contactName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getContactName()
	{
		return $this->contactName;
	}
		
	/**
	 * @param \Entity\Contact\Phone
	 * @return \Entity\Rental\Rental
	 */
	public function addPhone(\Entity\Contact\Phone $phone)
	{
		if(!$this->phones->contains($phone)) {
			$this->phones->add($phone);
		}
		$phone->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Phone
	 * @return \Entity\Rental\Rental
	 */
	public function removePhone(\Entity\Contact\Phone $phone)
	{
		$this->phones->removeElement($phone);
		$phone->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Contact\Phone[]
	 */
	public function getPhones()
	{
		return $this->phones;
	}
		
	/**
	 * @param \Entity\Contact\Email
	 * @return \Entity\Rental\Rental
	 */
	public function addEmail(\Entity\Contact\Email $email)
	{
		if(!$this->emails->contains($email)) {
			$this->emails->add($email);
		}
		$email->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Email
	 * @return \Entity\Rental\Rental
	 */
	public function removeEmail(\Entity\Contact\Email $email)
	{
		$this->emails->removeElement($email);
		$email->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Contact\Email[]
	 */
	public function getEmails()
	{
		return $this->emails;
	}
		
	/**
	 * @param \Entity\Contact\Url
	 * @return \Entity\Rental\Rental
	 */
	public function addUrl(\Entity\Contact\Url $url)
	{
		if(!$this->urls->contains($url)) {
			$this->urls->add($url);
		}
		$url->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Url
	 * @return \Entity\Rental\Rental
	 */
	public function removeUrl(\Entity\Contact\Url $url)
	{
		$this->urls->removeElement($url);
		$url->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Contact\Url[]
	 */
	public function getUrls()
	{
		return $this->urls;
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
		$this->spokenLanguages->removeElement($spokenLanguage);
		$spokenLanguage->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Language[]
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
		$this->amenities->removeElement($amenity);
		$amenity->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Amenity[]
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
		$this->tags->removeElement($tag);
		$tag->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Tag[]
	 */
	public function getTags()
	{
		return $this->tags;
	}
		
	/**
	 * @param integer
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
	 * @return integer|NULL
	 */
	public function getCheckIn()
	{
		return $this->checkIn;
	}
		
	/**
	 * @param integer
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
	 * @return integer|NULL
	 */
	public function getCheckOut()
	{
		return $this->checkOut;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Rental\Rental
	 */
	public function setPricesUponRequest($pricesUponRequest)
	{
		$this->pricesUponRequest = $pricesUponRequest;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getPricesUponRequest()
	{
		return $this->pricesUponRequest;
	}

	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetPrice()
	{
		$this->price = NULL;

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Image
	 * @return \Entity\Rental\Rental
	 */
	public function addImage(\Entity\Rental\Image $image)
	{
		if(!$this->images->contains($image)) {
			$this->images->add($image);
		}
		$image->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Image
	 * @return \Entity\Rental\Rental
	 */
	public function removeImage(\Entity\Rental\Image $image)
	{
		$this->images->removeElement($image);
		$image->unsetRental();

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\PricelistRow
	 * @return \Entity\Rental\Rental
	 */
	public function addPricelistRow(\Entity\Rental\PricelistRow $pricelistRow)
	{
		if(!$this->pricelistRows->contains($pricelistRow)) {
			$this->pricelistRows->add($pricelistRow);
		}
		$pricelistRow->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\PricelistRow
	 * @return \Entity\Rental\Rental
	 */
	public function removePricelistRow(\Entity\Rental\PricelistRow $pricelistRow)
	{
		$this->pricelistRows->removeElement($pricelistRow);
		$pricelistRow->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\PricelistRow[]
	 */
	public function getPricelistRows()
	{
		return $this->pricelistRows;
	}
		
	/**
	 * @param \Entity\Rental\Pricelist
	 * @return \Entity\Rental\Rental
	 */
	public function addPricelist(\Entity\Rental\Pricelist $pricelist)
	{
		if(!$this->pricelists->contains($pricelist)) {
			$this->pricelists->add($pricelist);
		}
		$pricelist->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Pricelist
	 * @return \Entity\Rental\Rental
	 */
	public function removePricelist(\Entity\Rental\Pricelist $pricelist)
	{
		$this->pricelists->removeElement($pricelist);
		$pricelist->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Pricelist[]
	 */
	public function getPricelists()
	{
		return $this->pricelists;
	}
		
	/**
	 * @param \Entity\Rental\InterviewAnswer
	 * @return \Entity\Rental\Rental
	 */
	public function addInterviewAnswer(\Entity\Rental\InterviewAnswer $interviewAnswer)
	{
		if(!$this->interviewAnswers->contains($interviewAnswer)) {
			$this->interviewAnswers->add($interviewAnswer);
		}
		$interviewAnswer->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\InterviewAnswer
	 * @return \Entity\Rental\Rental
	 */
	public function removeInterviewAnswer(\Entity\Rental\InterviewAnswer $interviewAnswer)
	{
		$this->interviewAnswers->removeElement($interviewAnswer);
		$interviewAnswer->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\InterviewAnswer[]
	 */
	public function getInterviewAnswers()
	{
		return $this->interviewAnswers;
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
		$this->fulltexts->removeElement($fulltext);
		$fulltext->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Fulltext[]
	 */
	public function getFulltexts()
	{
		return $this->fulltexts;
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
		$this->backLinks->removeElement($backLink);
		$backLink->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Seo\BackLink[]
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
		
	/**
	 * @param \Entity\Rental\Service
	 * @return \Entity\Rental\Rental
	 */
	public function addService(\Entity\Rental\Service $service)
	{
		if(!$this->services->contains($service)) {
			$this->services->add($service);
		}
		$service->setRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Service
	 * @return \Entity\Rental\Rental
	 */
	public function removeService(\Entity\Rental\Service $service)
	{
		$this->services->removeElement($service);
		$service->unsetRental();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Service[]
	 */
	public function getServices()
	{
		return $this->services;
	}
}