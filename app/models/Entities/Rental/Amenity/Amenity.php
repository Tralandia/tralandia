<?php

namespace Entities\Rental\Amenity;

use Entities\Dictionary;
use Entities\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_amenity_amenity")
 */
class Amenity extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Group", inversedBy="amenities")
	 */
	protected $group;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Rental\Rental", inversedBy="amenities")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

}