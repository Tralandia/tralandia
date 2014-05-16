<?php

namespace Entity\Rental;

use Entity\Phrase;
use Entity\Location;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Extras\Annotation as EA;
use Nette\DateTime;
use Nette\Http\Url;
use Nette\Utils\Arrays;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use Tralandia\Rental\CalendarManager;

/**
 * @ORM\Entity
 * @ORM\Table(name="rental",
 * 		indexes={
 * 			@ORM\Index(name="status", columns={"status"}),
 * 			@ORM\Index(name="slug", columns={"slug"}),
 * 			@ORM\Index(name="personalSiteUrl", columns={"personalSiteUrl"}),
 * 			@ORM\Index(name="rank", columns={"rank"}),
 * 			@ORM\Index(name="calendarUpdated", columns={"calendarUpdated"}),
 * 			@ORM\Index(name="harvested", columns={"harvested"}),
 * 			@ORM\Index(name="registeredFromEmail", columns={"registeredFromEmail"}),
 * 			@ORM\Index(name="backlinkEmailSent", columns={"backlinkEmailSent"}),
 * 			@ORM\Index(name="emailSent", columns={"emailSent"})
 * 		})
 *
 *
 * @method setRegisteredFromEmail()
 * @method setLastUpdate()
 * @method \Entity\Rental\CustomPricelistRow[] getCustomPricelistRows()
 * @method setDescription(\Entity\Phrase\Phrase $phrase)
 * @method \Entity\Phrase\Phrase getDescription()
 */
class Rental extends \Entity\BaseEntity implements \Security\IOwnerable
{

	use \Tralandia\Rental\TGetCalendar;

	const STATUS_DRAFT = 0;

	const STATUS_LIVE = 6;

	const BY_AGREEMENT = 'byAgreement';
	const ANYTIME = 'anytime';

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User", inversedBy="rentals", cascade={"persist"})
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
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
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Information", cascade={"persist"})
	 * @ORM\JoinTable(name="_information_rental",
	 *      joinColumns={@ORM\JoinColumn(name="rental_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="information_id", referencedColumnName="id", onDelete="CASCADE")}
	 *      )
	 */
	protected $missingInformation;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Contact\Address", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $address;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Placement")
	 * @ORM\JoinTable(name="_placement_rental",
	 *      joinColumns={@ORM\JoinColumn(name="rental_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="placement_id", referencedColumnName="id", onDelete="CASCADE")}
	 *      )
	 */
	protected $placements;

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
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $contactName;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Phone", cascade={"persist"})
	 */
	protected $phone;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $url;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $personalSiteUrl;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $email;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Language")
	 * @ORM\JoinTable(name="_language_rental",
	 *      joinColumns={@ORM\JoinColumn(name="rental_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="language_id", referencedColumnName="id")}
	 *      )
	 */
	protected $spokenLanguages;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Amenity")
	 * @ORM\JoinTable(name="_amenity_rental",
	 *      joinColumns={@ORM\JoinColumn(name="rental_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="amenity_id", referencedColumnName="id", onDelete="CASCADE")}
	 *      )
	 */
	protected $amenities;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $checkIn;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $checkOut;

	/**
	 * #notUsed 6/8/2013
	 * @var Boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $pricesUponRequest = FALSE;

	/**
	 * @var \Entity\Currency
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $currency;

	/**
	 * @var price
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $price;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="CustomPricelistRow", mappedBy="rental", cascade={"persist", "remove"})
	 * @ORM\OrderBy({"sort" = "ASC"})
	 */
	protected $customPricelistRows;

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
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $oldCalendar;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $oldCalendarUpdated;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Image", mappedBy="rental", cascade={"persist"})
	 * @ORM\OrderBy({"sort" = "ASC"})
	 */
	protected $images;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Video", mappedBy="rental", cascade={"persist"})
	 * @ORM\OrderBy({"sort" = "ASC"})
	 */
	protected $videos;

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
	 * @ORM\OrderBy({"dateTo" = "ASC"})
	 */
	protected $services;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Unit", mappedBy="rental", cascade={"persist"})
	 */
	protected $units;

	/**
	 * toto nieje stlpec v DB je to len pomocna premenna
	 * @var array
	 */
	private $sortedImages;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $harvested = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $registeredFromEmail;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $emailSent = FALSE;


	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $backlinkEmailSent = FALSE;


	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $lastUpdate;

	/**
	 * @return integer|NULL
	 */
	public function getMaxCapacity()
	{
		$max = 0;
		/** @var $unit Unit */
		foreach($this->units as $unit) {
			$max += $unit->getMaxCapacity();
		}
		return $max;
	}

	/**
	 * @return string|NULL
	 */
	public function getContactEmail()
	{
		return $this->email ? $this->email : $this->getOwner()->getLogin();
	}


	/**
	 * @return \Entity\Rental\Service
	 */
	public function getLastService($type = \Entity\Rental\Service::TYPE_FEATURED)
	{
		return $this->getServiceByType($type)->last();
	}


	/**
	 * @param $type
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getServiceByType($type)
	{
		return $this->services->filter(function($value) use ($type) {
			return $value->serviceType == $type;
		});
	}


	public function getMainImage()
	{
		return $this->getImage(0);
	}

	public function getImage($offset = 0)
	{
		$t = $this->getImages(1, $offset);
		$t = reset($t);

		return $t;
	}


	/**
	 * @param null $limit
	 * @param int $offset
	 *
	 * @return array|Image[]
	 */
	public function getImages($limit = NULL, $offset = 0)
	{
		$offset < 0 && $offset = 0;
		$images = $this->images->slice($offset, $limit);
		return $images;
	}


	/**
	 * @return \Entity\Rental\Video
	 */
	public function getMainVideo()
	{
		$t = $this->getVideos(1);
		$t = reset($t);

		return $t;
	}


	/**
	 * @param null $limit
	 * @param int $offset
	 *
	 * @return array|\Entity\Rental\Video[]
	 */
	public function getVideos($limit = NULL, $offset = 0)
	{
		$videos = $this->videos->slice($offset, $limit);
		return $videos;
	}


	public function addUnit(\Entity\Rental\Unit $unit)
	{
		if(!$this->units->contains($unit)) {
			$this->units->add($unit);
		}
		$unit->setRental($this);

		return $this;
	}

	public function removeUnit(\Entity\Rental\Unit $unit)
	{
		$this->units->removeElement($unit);
		$unit->unsetRental();

		return $this;
	}



	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getBoardAmenities()
	{
		return $this->getAmenitiesByType('board');
	}

	/**
	 * @return Amenity|null
	 */
	public function getPetAmenity()
	{
		$pet = $this->getAmenitiesByType('animal', 1);
		if (count($pet)) {
			return $pet[0];
		}

		return FALSE;
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getChildrenAmenities()
	{
		return $this->getAmenitiesByType('children');
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getServiceAmenities()
	{
		return $this->getAmenitiesByType('service');
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getWellnessAmenities()
	{
		return $this->getAmenitiesByType('wellness');
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getKitchenAmenities()
	{
		return $this->getAmenitiesByType('kitchen');
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getBathroomAmenities()
	{
		return $this->getAmenitiesByType('bathroom');
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getNearByAmenities()
	{
		return $this->getAmenitiesByType('near-by');
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getRentalServicesAmenities()
	{
		return $this->getAmenitiesByType('rental-services');
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getOnFacilityAmenities()
	{
		return $this->getAmenitiesByType('on-premises');
	}

	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getSportsFunAmenities()
	{
		return $this->getAmenitiesByType('sports-fun');
	}

	public function getCheckInFormatted()
	{
		return \Tools::$checkInOutOption[$this->checkIn];
	}


	public function getCheckOutFormatted()
	{
		return \Tools::$checkInOutOption[$this->checkOut];
	}


	/**
	 * @param $types
	 * @param null $limit
	 *
	 * @return \Entity\Rental\Amenity[]
	 */
	public function getAmenitiesByType($types, $limit = NULL)
	{
		$returnJustOneType = NULL;
		if (!is_array($types)) {
			$returnJustOneType = $types;
			$types = array($types);
		}

		$return = array();
		$i = 0;
		foreach ($this->getAmenities() as $amenity) {
			if (in_array($amenity->type->slug, $types)) {
				$return[$amenity->type->slug][] = $amenity;
				$i++;
			}

			if (is_numeric($limit)) {
				if ($i == $limit) break;
			}
		}

		if ($returnJustOneType) {
			$return = Arrays::get($return, $returnJustOneType, array());
		}

		return $return;
	}


	public function getImportantAmenities()
	{
		return $this->getAmenitiesByImportant(TRUE);
	}


	public function getAmenitiesByImportant($important = TRUE)
	{

		$return = array();
		foreach ($this->getAmenities() as $amenity) {
			if ($amenity->getImportant() == $important) {
				$return[$amenity->getId()] = $amenity;
			}
		}

		return $return;
	}


	/**
	 * @return array
	 */
	public function getImportantAmenitiesGroupByType()
	{
		return $this->getAmenitiesByImportantGroupByType(TRUE);
	}


	/**
	 * @return array
	 */

	public function getNotImportantAmenitiesGroupByType($excluded = NULL)

	{
		return $this->getAmenitiesByImportantGroupByType(FALSE, $excluded);
	}



	/**
	 * @param bool $important
	 *
	 * @return array
	 */

	public function getAmenitiesByImportantGroupByType($important = TRUE, $excluded = NULL)
	{
		$return = [];
		$sort = [];
		foreach ($this->getAmenities() as $amenity) {
			if ($important === NULL || $amenity->getImportant() == $important) {
				$type = $amenity->getType();
				if(is_array($excluded) && in_array($type->getSlug(), $excluded)) continue;

				$sort[$type->getId()] = $type->getSorting();
				$return[$type->getId()][$amenity->getId()] = $amenity;
			}
		}
		asort($sort);

		foreach($sort as $key => $value) {
			$sort[$key] = $return[$key];
		}

		return $sort;
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


	/**
	 * @param \Entity\Currency $currency
	 */
	public function setCurrency(\Entity\Currency $currency)
	{
		$this->currency = $currency;
	}

	/**
	 * @return \Entity\Currency
	 */
	public function getCurrency()
	{
		return $this->currency ? : $this->getAddress()->getPrimaryLocation()->getDefaultCurrency();
	}


	/**
	 * @param string
	 *
	 * @return \Entity\Rental\Rental
	 */
	public function setSlug($slug)
	{
		$this->slug = Strings::webalize(Strings::truncate($slug, 40, ''));

		return $this;
	}


	public function getCompulsoryMissingInformation()
	{
		return $this->missingInformation->filter(function ($e) {
			/** @var $e \Entity\Rental\Information */
			return $e->getCompulsory();
		});
	}


	/**
	 * @return bool
	 */
	public function isLive()
	{
		return $this->status == \Entity\Rental\Rental::STATUS_LIVE;
	}


	public function getOwnerId()
	{
		return $this->getUser()->getId();
	}


	/**
	 * Alias to getUser()
	 * @return \Entity\User\User|NULL
	 */
	public function getOwner()
	{
		return $this->getUser();
	}


	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		if ($address = $this->getAddress()) {
			return $address->getPrimaryLocation();
		}

		return NULL;
	}


	/**
	 * @return \Entity\Rental\InterviewAnswer|NULL
	 */
	public function getFirstInterviewAnswer()
	{
		if (isset($this->interviewAnswers[0])) {
			return $this->interviewAnswers[0];
		}

		return NULL;
	}

	public function hasFirstInterviewAnswer()
	{
		return isset($this->interviewAnswers[0]);
	}


	/**
	 * @return Bool
	 */
	public function getAnimalsAllowed()
	{
		$animals = $this->getAmenitiesByType(array('animal'));

		return (bool)count($animals) >= 1;
	}



	/**
	 * @return \Entity\Rental\Amenity
	 */
	public function getOwnerAvailability()
	{
		$t = $this->getAmenitiesByType('contact-person-availability');
		if (count($t) > 0) {
			return $t[0];
		} else {
			return NULL;
		}
	}


	/**
	 * @param array $calendar
	 * @param \DateTime $updated
	 *
	 * @return $this
	 */
	public function updateCalendar(array $calendar, \DateTime $updated = NULL)
	{
		if($updated === NULL) {
			$updated = new \DateTime();
		}

		$this->setCalendarUpdated($updated);
		$this->setCalendar($calendar);

		return $this;
	}


	/**
	 * @param array|\DateTime[]
	 *
	 * @return \Entity\Rental\Rental
	 */
	public function setCalendar(array $calendar)
	{
		$calendar = Json::encode($calendar);

		$this->calendar = $calendar;
		$this->formattedCalendar = NULL;

		return $this;
	}


	/**
	 * @param \DateTime $from
	 * @param \DateTime $to
	 *
	 * @return bool
	 */
	public function isAvailable(\DateTime $from, \DateTime $to = NULL)
	{
		$calendar = $this->getCalendar();

		if(!count($calendar)) return TRUE;

		if($to === NULL) $to = clone $from;

		$from->modify('midnight');
		while($from <= $to) {
			if(in_array($from, $calendar)) return FALSE;
			$from->modify('next day');
		}

		return TRUE;
	}

	/**
	 * @return \Entity\Rental\Placement|NULL
	 */
	public function getPlacement()
	{
		if ($this->hasPlacement()) {
			return $this->getPlacements()->first();
		}
		return NULL;
	}

	/**
	 * @return true|false
	 */
	public function hasPlacement()
	{
		return $this->placements->count() ? TRUE : FALSE;
	}

	public function setPlacement(Placement $placement)
	{
		$this->placements->clear();
		$this->addPlacement($placement);
	}

	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setUrl($url)
	{
		if(!$url) {
			$this->url = NULL;
			return $this;
		}

		if(!Strings::startsWith($url, 'http://') && !Strings::startsWith($url, 'https://')) {
			$url = 'http://' . $url;
		}

		if(!$url instanceof Url) {
			$url = new Url($url);
		}

		$this->url = "$url";

		return $this;
	}


	/**
	 * @return NULL|string
	 */
	public function getUrlWithoutProtocol()
	{
		$url = $this->getUrl();
		if(Strings::startsWith($url, 'http://')) {
			$url = substr($url, 7);
		}

		return $url;
	}


	public function getUrlObject()
	{
		return new Url($this->url);
	}



	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();

		$this->missingInformation = new \Doctrine\Common\Collections\ArrayCollection;
		$this->placements = new \Doctrine\Common\Collections\ArrayCollection;
		$this->spokenLanguages = new \Doctrine\Common\Collections\ArrayCollection;
		$this->amenities = new \Doctrine\Common\Collections\ArrayCollection;
		$this->customPricelistRows = new \Doctrine\Common\Collections\ArrayCollection;
		$this->pricelistRows = new \Doctrine\Common\Collections\ArrayCollection;
		$this->pricelists = new \Doctrine\Common\Collections\ArrayCollection;
		$this->interviewAnswers = new \Doctrine\Common\Collections\ArrayCollection;
		$this->images = new \Doctrine\Common\Collections\ArrayCollection;
		$this->videos = new \Doctrine\Common\Collections\ArrayCollection;
		$this->units = new \Doctrine\Common\Collections\ArrayCollection;
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

		return $this;
	}

	/**
	 * @param \Entity\Rental\Information
	 * @return \Entity\Rental\Rental
	 */
	public function removeMissingInformation(\Entity\Rental\Information $missingInformation)
	{
		$this->missingInformation->removeElement($missingInformation);

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
	 * @param \Entity\Rental\Placement
	 * @return \Entity\Rental\Rental
	 */
	public function addPlacement(\Entity\Rental\Placement $placement)
	{
		if(!$this->placements->contains($placement)) {
			$this->placements->add($placement);
		}

		return $this;
	}

	/**
	 * @param \Entity\Rental\Placement
	 * @return \Entity\Rental\Rental
	 */
	public function removePlacement(\Entity\Rental\Placement $placement)
	{
		$this->placements->removeElement($placement);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Placement[]
	 */
	public function getPlacements()
	{
		return $this->placements;
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
	public function setPhone(\Entity\Contact\Phone $phone)
	{
		$this->phone = $phone;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetPhone()
	{
		$this->phone = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Phone|NULL
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetUrl()
	{
		$this->url = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string
	 * @return \Entity\Rental\Rental
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetEmail()
	{
		$this->email = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getEmail()
	{
		return $this->email;
	}

	public function setSpokenLanguages(array $spokenLanguages)
	{
		$this->spokenLanguages->clear();
		foreach($spokenLanguages as $language) {
			$this->addSpokenLanguage($language);
		}

		return $this;
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

		return $this;
	}

	/**
	 * @param \Entity\Language
	 * @return \Entity\Rental\Rental
	 */
	public function removeSpokenLanguage(\Entity\Language $spokenLanguage)
	{
		$this->spokenLanguages->removeElement($spokenLanguage);

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

		return $this;
	}

	/**
	 * @param \Entity\Rental\Amenity
	 * @return \Entity\Rental\Rental
	 */
	public function removeAmenity(\Entity\Rental\Amenity $amenity)
	{
		$this->amenities->removeElement($amenity);

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
	 * @param \Entity\Rental\CustomPricelistRow
	 * @return \Entity\Rental\Rental
	 */
	public function addCustomPricelistRow(\Entity\Rental\CustomPricelistRow $pricelistRow)
	{
		if(!$this->customPricelistRows->contains($pricelistRow)) {
			$this->customPricelistRows->add($pricelistRow);
		}
		$pricelistRow->setRental($this);

		return $this;
	}

	/**
	 * @param \Entity\Rental\CustomPricelistRow
	 * @return \Entity\Rental\Rental
	 */
	public function removeCustomPricelistRow(\Entity\Rental\CustomPricelistRow $pricelistRow)
	{
		$this->customPricelistRows->removeElement($pricelistRow);
		$pricelistRow->unsetRental();

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
	 * @return array|\Entity\Rental\InterviewAnswer[]
	 */
	public function getInterviewAnswers()
	{
		return $this->interviewAnswers;
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
	 * @param \DateTime
	 * @return \Entity\Rental\Rental
	 */
	public function setCalendarUpdated(\DateTime $calendarUpdated)
	{
		$this->calendarUpdated = $calendarUpdated;

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
	 * @param \DateTime
	 * @return \Entity\Rental\Rental
	 */
	public function setOldCalendarUpdated(\DateTime $calendarUpdated)
	{
		$this->oldCalendarUpdated = $calendarUpdated;

		return $this;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getOldCalendarUpdated()
	{
		return $this->oldCalendarUpdated;
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
	 * @param \Entity\Rental\Video
	 * @return \Entity\Rental\Rental
	 */
	public function addVideo(\Entity\Rental\Video $video)
	{
		if(!$this->videos->contains($video)) {
			$this->videos->add($video);
		}
		$video->setRental($this);

		return $this;
	}

	/**
	 * @param \Entity\Rental\Video
	 * @return \Entity\Rental\Rental
	 */
	public function removeVideo(\Entity\Rental\Video $video)
	{
		$this->videos->removeElement($video);
		$video->unsetRental();

		return $this;
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
	 * @param integer
	 * @return \Entity\Rental\Rental
	 */
	public function setBedroomCount($bedroomCount)
	{
		$this->bedroomCount = $bedroomCount;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Rental
	 */
	public function unsetBedroomCount()
	{
		$this->bedroomCount = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getBedroomCount()
	{
		return $this->bedroomCount;
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
