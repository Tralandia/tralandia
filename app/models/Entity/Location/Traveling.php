<?php

namespace Entity\Location;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_traveling")
 */
class Traveling extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location", inversedBy="outgoingLocations")
	 */
	protected $sourceLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Location", inversedBy="incomingLocations")
	 */
	protected $destinationLocation;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $peopleCount;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $year;

	//@entity-generator-code


}