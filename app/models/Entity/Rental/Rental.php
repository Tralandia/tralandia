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

}