<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="test")
 */
class Test extends \Entity\BaseEntity {

	/**
	 * @ORM\ManyToOne(targetEntity="Entity\Contacts\Email", cascade={"persist", "remove"})
	 */
	public $email;

	/**
	 * @ORM\ManyToOne(targetEntity="Entity\Contacts\Address", cascade={"persist", "remove"})
	 */
	public $address;

	/**
	 * @ORM\ManyToOne(targetEntity="Entity\Contacts\Phone", cascade={"persist", "remove"})
	 */
	public $phone;

	/**
	 * @ORM\ManyToOne(targetEntity="Entity\Contacts\Url", cascade={"persist", "remove"})
	 */
	public $url;


	public function setEmail(\Entity\Contacts\Email $email = null) {
		$this->email = $email;
	}
	public function getEmail() {
		return $this->email;
	}
	public function setPhone(\Entity\Contacts\Phone $phone = null) {
		$this->phone = $phone;
	}
	public function getPhone() {
		return $this->phone;
	}
	public function setAddress(\Entity\Contacts\Address $address = null) {
		$this->address = $address;
	}
	public function getAddress() {
		return $this->address;
	}
	public function setUrl(\Entity\Contacts\Url $url = null) {
		$this->url = $url;
	}
	public function getUrl() {
		return $this->url;
	}
}