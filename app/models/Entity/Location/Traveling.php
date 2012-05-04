<?php

namespace Entity\Location;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_traveling", indexes={@ORM\index(name="peopleCount", columns={"peopleCount"}), @ORM\index(name="year", columns={"year"})})
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

	




















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Location\Traveling
	 */
	public function setSourceLocation(\Entity\Location\Location $sourceLocation) {
		$this->sourceLocation = $sourceLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Traveling
	 */
	public function unsetSourceLocation() {
		$this->sourceLocation = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getSourceLocation() {
		return $this->sourceLocation;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Location\Traveling
	 */
	public function setDestinationLocation(\Entity\Location\Location $destinationLocation) {
		$this->destinationLocation = $destinationLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Traveling
	 */
	public function unsetDestinationLocation() {
		$this->destinationLocation = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getDestinationLocation() {
		return $this->destinationLocation;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Location\Traveling
	 */
	public function setPeopleCount($peopleCount) {
		$this->peopleCount = $peopleCount;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Traveling
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
	 * @return \Entity\Location\Traveling
	 */
	public function setYear($year) {
		$this->year = $year;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Traveling
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