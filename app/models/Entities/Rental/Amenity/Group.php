<?php

namespace Entities\Rental\Amenity;

use Entities\Dictionary;
use Entities\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_amenity_group")
 */
class Group extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Amenity", mappedBy="goup")
	 */
	protected $amenities;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

}