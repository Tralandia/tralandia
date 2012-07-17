<?php

namespace Entity\Location;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_location", indexes={@ORM\index(name="name", columns={"name_id"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="parentId", columns={"parentId"}), @ORM\index(name="latitude", columns={"latitude"}), @ORM\index(name="longitude", columns={"longitude"})})
 * @EA\Service(name="\Service\Location\Location")
 * @EA\ServiceList(name="\Service\Location\LocationList")
 * @EA\Primary(key="id", value="slug")
 */
class Location extends \Entity\BaseEntityDetails {

	const STATUS_DRAFT = 'draft';
	const STATUS_LAUNCHED = 'launched';

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameOfficial;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameShort;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $parentId;


	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\RegionType", inversedBy="locations")
	 */
	protected $regionType;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $polygon;

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
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $defaultZoom;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $clickMapData;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Company\BankAccount", inversedBy="countries")
	 */
	protected $bankAccounts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Company\Company", inversedBy="countries")
	 */
	protected $companies;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Company\Office", inversedBy="countries")
	 */
	protected $offices;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Domain", inversedBy="locations")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Invoicing\Marketing", inversedBy="locations")
	 */
	protected $marketings;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="locations")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\User\User", mappedBy="location", cascade={"persist"})
	 */
	protected $users;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Traveling", mappedBy="destinationLocation")
	 */
	protected $incomingLocations;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Traveling", mappedBy="sourceLocation")
	 */
	protected $outgoingLocations;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Seo\BackLink", mappedBy="location")
	 */
	protected $backLinks;

	/* ----------------------------- attributes from country ----------------------------- */

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $status;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iso;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iso3;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Currency", cascade={"persist"})
	 */
	protected $defaultCurrency;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Currency", mappedBy="locations", cascade={"persist"})
	 */
	protected $currencies;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language", cascade={"persist"})
	 */
	protected $defaultLanguage;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Dictionary\Language", mappedBy="locations", cascade={"persist"})
	 */
	protected $languages;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $population;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $phonePrefix;

	/**
	 * @var url
	 * @ORM\Column(type="url", nullable=true)
	 */
	protected $facebookGroup;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $capitalCity;

	/**
	 * @var phone
	 * @ORM\Column(type="phone", nullable=true)
	 */
	protected $phoneNumberEmergency;

	/**
	 * @var phone
	 * @ORM\Column(type="phone", nullable=true)
	 */
	protected $phoneNumberPolice;

	/**
	 * @var phone
	 * @ORM\Column(type="phone", nullable=true)
	 */
	protected $phoneNumberMedical;

	/**
	 * @var phone
	 * @ORM\Column(type="phone", nullable=true)
	 */
	protected $phoneNumberFire;

	/**
	 * @var url
	 * @ORM\Column(type="url", nullable=true)
	 */
	protected $wikipediaLink;

	/**
	 * @var contacts
	 * @ORM\Column(type="contacts", nullable=true)
	 */
	protected $contacts;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $drivingSide;

	/**
	 * @var price
	 * @ORM\Column(type="price", nullable=true)
	 */
	protected $pricesPizza;

	/**
	 * @var price
	 * @ORM\Column(type="price", nullable=true)
	 */
	protected $pricesDinner;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $airports;

//@entity-generator-code <--- NEMAZAT !!!

}