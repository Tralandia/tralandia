<?php

namespace Entities\Location;

use Entities\Location;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_traveling")
 */
class Traveling extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Location")
	 */
	protected $source;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Location")
	 */
	protected $destination;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $peopleCount;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $year;


	public function __construct() {
		parent::__construct();

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
