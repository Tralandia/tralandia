<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use    Extras\Annotation as EA;

/**
 * @ORM\Entity
 * @ORM\Table(name="rental_type")
 *
 *
 */
class Type extends \Entity\BaseEntity
{

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
	 * @ORM\OneToMany(targetEntity="Rental", mappedBy="type", cascade={"persist"})
	 */
	protected $rentals;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $classification = FALSE;


	/**
	 * @param string
	 *
	 * @return \Entity\Rental\AmenityType
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}


	/**
	 * @return bool
	 */
	public function hasClassification()
	{
		return $this->classification;
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
	 * @return \Entity\Rental\Type
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
	 * @return \Entity\Rental\Type
	 */
	public function addRental(\Entity\Rental\Rental $rental)
	{
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}
		$rental->setType($this);

		return $this;
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Type
	 */
	public function removeRental(\Entity\Rental\Rental $rental)
	{
		$this->rentals->removeElement($rental);
		$rental->unsetType();

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
	 * @param boolean
	 * @return \Entity\Rental\Type
	 */
	public function setClassification($classification)
	{
		$this->classification = $classification;

		return $this;
	}

	/**
	 * @return boolean|NULL
	 */
	public function getClassification()
	{
		return $this->classification;
	}
}
