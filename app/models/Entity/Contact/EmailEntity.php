<?php

namespace Entity\Contact;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_email", indexes={@ORM\index(name="value", columns={"value"})})
*/
class Email extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $value;

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
	 * @return \Entity\Contact\Email
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
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Contact\Email
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