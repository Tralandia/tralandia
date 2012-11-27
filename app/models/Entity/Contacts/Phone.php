<?php

namespace Entity\Contacts;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="phone", indexes={@ORM\index(name="phone", columns={"phone"})})
 * @EA\Primary(key="id", value="phone")
 */
class Phone extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $phone;
		
	/**
	 * @param string
	 * @return Phone
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPhone() {
		return $this->phone;
	}
}