<?php

namespace Entity\Contacts;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phone", indexes={@ORM\index(name="value", columns={"value"})})
 */
class Phone extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20)
	 */
	protected $value;
		
	/**
	 * @var string
	 * @ORM\Column(type="string", length=20)
	 */
	protected $international;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=20)
	 */
	protected $national;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="phones")
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
	 * @param string
	 * @return \Entity\Contacts\Phone
	 */
	public function setValue($value)
	{
		$this->value = $value;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getValue()
	{
		return $this->value;
	}
		
	/**
	 * @param string
	 * @return \Entity\Contacts\Phone
	 */
	public function setInternational($international)
	{
		$this->international = $international;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getInternational()
	{
		return $this->international;
	}
		
	/**
	 * @param string
	 * @return \Entity\Contacts\Phone
	 */
	public function setNational($national)
	{
		$this->national = $national;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getNational()
	{
		return $this->national;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Contacts\Phone
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Contacts\Phone
	 */
	public function unsetPrimaryLocation()
	{
		$this->primaryLocation = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Contacts\Phone
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