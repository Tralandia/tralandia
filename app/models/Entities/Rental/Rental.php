<?php

namespace Entities\Rental;

use Entities\Contact;
use Entities\Dictionary;
use Entities\Invoicing;
use Entities\Location;
use Entities\Medium;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_rental")
 */
class Rental extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\User", inversedBy="rentals")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
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
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", mappedBy="rentals")
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
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $briefDescription;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $teaser;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Contact\Contact", mappedBy="rentals")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Dictionary\Language", mappedBy="rentals")
	 */
	protected $languagesSpoken;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Rental\Amenity\Amenity", mappedBy="rentals")
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
	 * @ORM\OneToMany(targetEntity="Entities\Medium\Medium", mappedBy="rental")
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
	 * @ORM\OneToMany(targetEntity="Entities\Invoicing\Invoice", mappedBy="rental")
	 */
	protected $invoices;

}