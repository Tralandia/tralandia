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
	 * @ORM\ManyToOne(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental\Type")
	 */
	protected $rentalType;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location\Location")
	 */
	protected $location;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $page;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental\Amenity\Amenity")
	 */
	protected $tag;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Attraction\Attraction")
	 */
	protected $attractionType;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Medium\Medium")
	 */
	protected $media;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $title;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $h1;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $tabName;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $ppcKeywords;

}