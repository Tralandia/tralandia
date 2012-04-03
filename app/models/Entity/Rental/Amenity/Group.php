<?php

namespace Entity\Rental\Amenity;

use Entity\Dictionary;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_amenity_group")
 */
class Group extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Amenity", mappedBy="goup")
	 */
	protected $amenities;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

}