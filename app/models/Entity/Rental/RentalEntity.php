<?php

namespace Entity\Rental;

use Entity\Phrase;
use Entity\Invoice;
use Entity\Location;
use Entity\Medium;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Rental\RentalRepository")
 * @ORM\Table(name="rental", indexes={@ORM\index(name="status", columns={"status"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="calendarUpdated", columns={"calendarUpdated"})})
 * @EA\Primary(key="id", value="slug")
 * @EA\Generator(skip="{getImages,getPriceSeason,getPriceOffSeason}")
 */
class Rental extends \Entity\BaseEntity {

	const STATUS_DRAFT = 0;
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
	 * @ORM\ManyToMany(targetEntity="Information", mappedBy="rentals")
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
	protected $priceSeason;

	/**
	 * @var price
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $priceOffSeason;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Image", mappedBy="rental", cascade={"persist"})
	 */
	protected $images;

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

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Referral", mappedBy="rental", cascade={"persist"})
	 */
	protected $referrals;

	public function getMainImage() {
		return $this->images->first();
	}

	public function getImages1($limit = NULL, $offset = 0) {
		return $this->images->slice($offset, $limit);
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
	public function getPriceSeason()
	{
		return new \Extras\Types\Price($this->priceSeason, $this->getCurrency());
	}

	/**
	 * @return \Extras\Types\Price
	 */
	public function getPriceOffSeason()
	{
		return new \Extras\Types\Price($this->priceOffSeason, $this->getCurrency());
	}

	public function getCurrency() {
		return $this->primaryLocation->defaultCurrency;
	}
		

	//@entity-generator-code --- NEMAZAT !!!
}