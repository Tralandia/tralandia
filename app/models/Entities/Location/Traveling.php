<?php

namespace Entities\Location;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_traveling")
 */
class Traveling extends BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location", inversedBy="travelings")
	 */
	protected $sourceLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location", inversedBy="incomings")
	 */
	protected $destinationLocation;

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