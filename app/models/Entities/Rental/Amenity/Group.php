<?php

namespace Entities\Rental\Amenity;

use Entities\Dictionary;
use Entities\Rental;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="rental_amenity_group")
 */
class Group extends \BaseEntity {

	/**
	 * @var Collection
	 * @Column(type="Amenity")
	 */
	protected $amenities;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;


	public function __construct() {

	}


	/**
	 * @param Amenity $amenities
	 * @return Group
	 */
	public function setAmenities(Amenity  $amenities) {
		$this->amenities = $amenities;
		return $this;
	}


	/**
	 * @return Amenity
	 */
	public function getAmenities() {
		return $this->amenities;
	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Group
	 */
	public function setName(Dictionary\Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}

}
