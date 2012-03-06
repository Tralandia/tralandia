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
	 * @ORM\ManyToOne(type="Group", inversedBy="amenities")
	 */
	protected $group;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

}