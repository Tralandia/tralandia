<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_information")
 * @EA\Primary(key="id", value="name")
 */
class Information extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $required = FALSE;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="missingInformations")
	 */
	protected $rentals;


		//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\Information
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
	 * @return \Entity\Rental\Information
	 */
	public function setRequired($required)
	{
		$this->required = $required;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getRequired()
	{
		return $this->required;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Information
	 */
	public function addRental(\Entity\Rental\Rental $rental)
	{
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getRentals()
	{
		return $this->rentals;
	}
}