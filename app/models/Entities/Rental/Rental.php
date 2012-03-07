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
class Rental extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User\User", inversedBy="rentals")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Dictionary\Language")
	 */
	protected $editLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Type")
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
	 * @ORM\OneToMany(targetEntity="Location\Location")
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
	 * @var webalizedString
	 * @ORM\Column(type="webalizedString")
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $briefDescription;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $teaser;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Contact\Contact")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Dictionary\Language")
	 */
	protected $languagesSpoken;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Rental\Amenity\Amenity")
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
	 * @ORM\OneToMany(targetEntity="Medium\Medium")
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
	 * @ORM\OneToMany(targetEntity="Invoicing\Invoice", mappedBy="rental")
	 */
	protected $invoices;

}