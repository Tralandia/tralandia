<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Rental\PlacementRepository")
 * @ORM\Table(name="rental_placement")
 * @EA\Primary(key="id", value="id")
 * @EA\Generator(skip="{setSlug}")
 */
class Placement extends \Entity\BaseEntity {

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
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Rental", inversedBy="placements", cascade={"persist"})
	 */
	protected $rentals;

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
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\Placement
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
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Placement
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
	 * @return \Entity\Rental\Placement
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
}