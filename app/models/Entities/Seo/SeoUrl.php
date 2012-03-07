<?php

namespace Entities\Seo;

use Entities\Attraction;
use Entities\Dictionary;
use Entities\Location;
use Entities\Medium;
use Entities\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="seo_seourl")
 */
class SeoUrl extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Rental\Type")
	 */
	protected $rentalType;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Location\Location")
	 */
	protected $location;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $page;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Rental\Amenity\Amenity")
	 */
	protected $tag;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Attraction\Attraction")
	 */
	protected $attractionType;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entities\Medium\Medium")
	 */
	protected $media;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $title;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $h1;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $tabName;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $ppcKeywords;

}