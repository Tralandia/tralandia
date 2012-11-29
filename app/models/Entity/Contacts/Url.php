<?php

namespace Entity\Contacts;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="url")
 */
class Url extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $value;
		
	/**
	 * @param string
	 * @return Url
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