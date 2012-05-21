<?php

namespace Entity\Rental;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_amenitytype", indexes={@ORM\index(name="slug", columns={"slug"})})
 */
class AmenityType extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Amenity", mappedBy="type")
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
	 * @param \Entity\Rental\Amenity
	 * @return \Entity\Rental\AmenityType
	 */
	public function addAmenity(\Entity\Rental\Amenity $amenity) {
		if(!$this->amenities->contains($amenity)) {
			$this->amenities->add($amenity);
		}
		$amenity->setType($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Amenity
	 * @return \Entity\Rental\AmenityType
	 */
	public function removeAmenity(\Entity\Rental\Amenity $amenity) {
		if($this->amenities->contains($amenity)) {
			$this->amenities->removeElement($amenity);
		}
		$amenity->unsetType();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Amenity
	 */
	public function getAmenities() {
		return $this->amenities;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Rental\AmenityType
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
	 * @param slug
	 * @return \Entity\Rental\AmenityType
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