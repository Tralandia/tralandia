<?php

namespace Location;

use Location;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="location_traveling")
 */
class Traveling extends \BaseEntity {

	/**
	 * @var Collection
	 * @Column(type="Location")
	 */
	protected $source;

	/**
	 * @var Collection
	 * @Column(type="Location")
	 */
	protected $destination;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $peopleCount;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $year;


	public function __construct() {

	}


	/**
	 * @param Location $source
	 * @return Traveling
	 */
	public function setSource(Location  $source) {
		$this->source = $source;
		return $this;
	}


	/**
	 * @return Location
	 */
	public function getSource() {
		return $this->source;
	}


	/**
	 * @param Location $destination
	 * @return Traveling
	 */
	public function setDestination(Location  $destination) {
		$this->destination = $destination;
		return $this;
	}


	/**
	 * @return Location
	 */
	public function getDestination() {
		return $this->destination;
	}


	/**
	 * @param integer $peopleCount
	 * @return Traveling
	 */
	public function setPeopleCount($peopleCount) {
		$this->peopleCount = $peopleCount;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getPeopleCount() {
		return $this->peopleCount;
	}


	/**
	 * @param integer $year
	 * @return Traveling
	 */
	public function setYear($year) {
		$this->year = $year;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getYear() {
		return $this->year;
	}

}
