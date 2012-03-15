<?php

namespace Entities\Location;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_location")
 */
class Location extends BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Dictionary\Language", mappedBy="locations")
	 */
	protected $languages;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $nameOfficial;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $nameShort;

	/**
	 * @var string
	 * @ORM\Column(type="string", unique=true)
	 */
	protected $iso;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $nestedLeft;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $nestedRight;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $polygon;

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
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $defaultZoom;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Company\BankAccount", inversedBy="countries")
	 */
	protected $bankAccounts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Company\Company", inversedBy="countries")
	 */
	protected $companies;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Company\Office", inversedBy="countries")
	 */
	protected $offices;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Domain", inversedBy="locations")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Invoicing\Marketing", inversedBy="locations")
	 */
	protected $marketings;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Rental\Rental", inversedBy="locations")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\User\User", inversedBy="locations")
	 */
	protected $users;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Traveling", mappedBy="destination")
	 */
	protected $incomings;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Traveling", mappedBy="source")
	 */
	protected $travelings;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Country", mappedBy="location")
	 */
	protected $country;

}