<?php

namespace Entity\Rental;

use Entity\Dictionary;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_amenity_amenity")
 */
class Amenity extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Group", inversedBy="amenities")
	 */
	protected $group;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="amenities")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;




















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Rental\Amenity\Group
	 * @return \Entity\Rental\Amenity\Amenity
	 */
	public function setGroup(\Entity\Rental\Amenity\Group $group) {
		$this->group = $group;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Amenity\Amenity
	 */
	public function unsetGroup() {
		$this->group = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Amenity\Group|NULL
	 */
	public function getGroup() {
		return $this->group;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Amenity\Amenity
	 */
	public function addRental(\Entity\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Rental\Amenity\Amenity
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
}