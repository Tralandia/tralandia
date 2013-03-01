<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Rental\AmenityRepository")
 * @ORM\Table(name="rental_amenity")
 * @EA\Primary(key="id", value="id")
 * @EA\Generator(skip="{setSlug}")
 */
class Amenity extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="AmenityType", inversedBy="amenities")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="amenities")
	 */
	protected $rentals;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;
	
	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $important = FALSE;


	/**
	 * @param string
	 * @return \Entity\Rental\AmenityType
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Rental\AmenityType
	 * @return \Entity\Rental\Amenity
	 */
	public function setType(\Entity\Rental\AmenityType $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Amenity
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\AmenityType|NULL
	 */
	public function getType()
	{
		return $this->type;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Amenity
	 */
	public function addRental(\Entity\Rental\Rental $rental)
	{
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Amenity
	 */
	public function removeRental(\Entity\Rental\Rental $rental)
	{
		$this->rentals->removeElement($rental);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Rental[]
	 */
	public function getRentals()
	{
		return $this->rentals;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\Amenity
	 */
	public function setName(\Entity\Phrase\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Rental\Amenity
	 */
	public function setImportant($important)
	{
		$this->important = $important;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getImportant()
	{
		return $this->important;
	}
}