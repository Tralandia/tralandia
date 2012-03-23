<?php

namespace Entities\Location;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_traveling")
 */
class Traveling extends \Entities\BaseEntity {

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
	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Location\Traveling
	 */
	public function setSourceLocation(\Entities\Location\Location $sourceLocation) {
		$this->sourceLocation = $sourceLocation;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getSourceLocation() {
		return $this->sourceLocation;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Location\Traveling
	 */
	public function setDestinationLocation(\Entities\Location\Location $destinationLocation) {
		$this->destinationLocation = $destinationLocation;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getDestinationLocation() {
		return $this->destinationLocation;
	}

	/**
	 * @param integer
	 * @return \Entities\Location\Traveling
	 */
	public function setPeopleCount($peopleCount) {
		$this->peopleCount = $peopleCount;

		return $this;
	}

	/**
	 * @return \Entities\Location\Traveling
	 */
	public function unsetPeopleCount() {
		$this->peopleCount = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getPeopleCount() {
		return $this->peopleCount;
	}

	/**
	 * @param integer
	 * @return \Entities\Location\Traveling
	 */
	public function setYear($year) {
		$this->year = $year;

		return $this;
	}

	/**
	 * @return \Entities\Location\Traveling
	 */
	public function unsetYear() {
		$this->year = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getYear() {
		return $this->year;
	}



}