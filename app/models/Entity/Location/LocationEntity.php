<?php

namespace Entity\Location;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Location\LocationRepository")
 * @ORM\Table(name="location", indexes={@ORM\index(name="name", columns={"name_id"}), @ORM\index(name="slug", columns={"slug"}), @ORM\index(name="latitude", columns={"latitude"}), @ORM\index(name="longitude", columns={"longitude"})})
 * @EA\Primary(key="id", value="slug")
 */
class Location extends \Entity\BaseEntityDetails {

	const STATUS_DRAFT = 'draft';
	const STATUS_LAUNCHED = 'launched';

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"}, fetch="EAGER")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameOfficial;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $nameShort;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location", cascade={"persist"})
	 */
	protected $parent;


	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

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
	 * @ORM\ManyToOne(targetEntity="Entity\Domain", inversedBy="locations")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Invoice\Marketing", inversedBy="locations")
	 */
	protected $marketings;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Rental\Rental", mappedBy="primaryLocation")
	 */
	protected $primaryRentals;

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
	 * @ORM\OneToMany(targetEntity="Entity\Seo\BackLink", mappedBy="location")
	 */
	protected $backLinks;

	/* ----------------------------- attributes from country ----------------------------- */

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
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $phonePrefix;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Currency", cascade={"persist"})
	 */
	protected $defaultCurrency;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language", cascade={"persist"})
	 */
	protected $defaultLanguage;

	/**
	 * @var contacts
	 * @ORM\Column(type="contacts", nullable=true)
	 */
	protected $contacts;

	//@entity-generator-code --- NEMAZAT !!!


}