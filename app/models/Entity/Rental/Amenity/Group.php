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
	 * @ORM\OneToMany(targetEntity="Amenity", mappedBy="group")
	 */
	protected $amenities;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;
	






//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->amenities = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Rental\Amenity\Amenity
	 * @return \Entity\Rental\Amenity\Group
	 */
	public function addAmenity(\Entity\Rental\Amenity\Amenity $amenity) {
		if(!$this->amenities->contains($amenity)) {
			$this->amenities->add($amenity);
		}
		$amenity->setGroup($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Amenity\Amenity
	 * @return \Entity\Rental\Amenity\Group
	 */
	public function removeAmenity(\Entity\Rental\Amenity\Amenity $amenity) {
		if($this->amenities->contains($amenity)) {
			$this->amenities->removeElement($amenity);
		}
		$amenity->unsetGroup();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Amenity\Amenity
	 */
	public function getAmenities() {
		return $this->amenities;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Rental\Amenity\Group
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
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Rental\Amenity\Group
	 */
	public function setNamePlural(\Entity\Dictionary\Phrase $namePlural) {
		$this->namePlural = $namePlural;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getNamePlural() {
		return $this->namePlural;
	}
		
	/**
	 * @param slug
	 * @return \Entity\Rental\Amenity\Group
	 */
	public function setSlug($slug) {
		$this->slug = $slug;

		return $this;
	}
		
	/**
	 * @return slug|NULL
	 */
	public function getSlug() {
		return $this->slug;
	}
}