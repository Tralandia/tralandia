<?php

namespace Entities\Location;

use Entities\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_traveling")
 */
class Traveling extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location", inversedBy="travelings")
	 */
	protected $source;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location", inversedBy="incomings")
	 */
	protected $destination;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $peopleCount;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $year;

}