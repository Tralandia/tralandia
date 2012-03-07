<?php

namespace Entities\Location;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_location")
 */
class Location extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Dictionary\Language")
	 */
	protected $languages;

	/**
	 * @var Collection
	 * @ORM\OntToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OntToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $nameOfficial;

	/**
	 * @var Collection
	 * @ORM\OntToOne(targetEntity="Dictionary\Phrase")
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
	 * @ORM\OneToMany(targetEntity="Travelink", mappedBy="destination")
	 */
	protected $incomings;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Travelink", mappedBy="source")
	 */
	protected $travelings;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Country", mappedBy="location")
	 */
	protected $country;

}