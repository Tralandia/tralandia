<?php

namespace Entity\Contacts;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="email", indexes={@ORM\index(name="email", columns={"email"})})
 * @EA\Primary(key="id", value="email")
 */
class Email extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email;
		
	/**
	 * @param string
	 * @return Email
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getEmail() {
		return $this->email;
	}
}