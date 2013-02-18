<?php

namespace Entity\Contact;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_phone", indexes={@ORM\index(name="value", columns={"value"})})
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
	 * @ORM\OneToOne(targetEntity="Entity\Rental\Rental")
	 */
	protected $rental;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Contact\Phone
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
	 * @return \Entity\Contact\Phone
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
	 * @return \Entity\Contact\Phone
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
	 * @return \Entity\Contact\Phone
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Phone
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
	 * @return \Entity\Contact\Phone
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental()
	{
		return $this->rental;
	}
}