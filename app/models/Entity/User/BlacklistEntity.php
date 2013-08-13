<?php

namespace Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maju zakaz posielat rezervacie
 * @ORM\Entity
 * @ORM\Table(name="user_blacklist", indexes={@ORM\index(name="email", columns={"email"})})
*/
class Blacklist extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string
	 * @return \Entity\User\Blacklist
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
