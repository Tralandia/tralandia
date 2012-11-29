<?php

namespace Entity\Contacts;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="email", indexes={@ORM\index(name="value", columns={"value"})})
 */
class Email extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $value;
		
	/**
	 * @param string
	 * @return Email
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getValue() {
		return $this->value;
	}
}