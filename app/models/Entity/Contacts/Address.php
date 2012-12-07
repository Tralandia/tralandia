<?php

namespace Entity\Contacts;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="address")
 */
class Address extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $row1;
	
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $row2;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $postcode;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $city;

	/**
	 * @var string
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @param string
	 * @return Address
	 */
	public function setRow1($row1) {
		$this->row1 = $row1;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getRow1() {
		return $this->row1;
	}

	/**
	 * @param string
	 * @return Address
	 */
	public function setRow2($row2) {
		$this->row2 = $row2;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getRow2() {
		return $this->row1;
	}

	/**
	 * @param string
	 * @return Address
	 */
	public function setCity($city) {
		$this->city = $city;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param string
	 * @return Address
	 */
	public function setPostcode($postcode) {
		$this->postcode = $postcode;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPostcode() {
		return $this->postcode;
	}

	/**
	 * @param Entity\Location\Location
	 * @return Address
	 */
	public function setCountry(\Entity\Location\Location $country) {
		$this->country = $country;
		return $this;
	}
		
	/**
	 * @return Entity\Location\Location|NULL
	 */
	public function getCountry() {
		return $this->country;
	}
}