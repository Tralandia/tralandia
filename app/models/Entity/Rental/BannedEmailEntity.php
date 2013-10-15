<?php

namespace Entity\Rental;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maju zakaz registracie
 * @ORM\Entity
 * @ORM\Table(name="rental_bannedemail", indexes={@ORM\Index(name="email", columns={"email"})})
 */
class BannedEmail extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", unique=true)
	 */
	protected $email;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */

	/**
	 * @param string
	 * @return \Entity\Rental\BannedEmail
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getEmail()
	{
		return $this->email;
	}
}
