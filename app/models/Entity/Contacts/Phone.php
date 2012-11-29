<?php

namespace Entity\Contacts;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phone", indexes={@ORM\index(name="value", columns={"value"})})
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
	 * @var string
	 * @ORM\Column(type="string", length=2)
	 */
	protected $region;

	/**
	 * @param string
	 * @return Phone
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

	/**
	 * @param string
	 * @return International
	 */
	public function setInternational($international) {
		$this->international = $international;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getInternational() {
		return $this->international;
	}

	/**
	 * @param string
	 * @return Region
	 */
	public function setRegion($region) {
		$this->region = $region;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * @param string
	 * @return National
	 */
	public function setNational($national) {
		$this->national = $national;
		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getNational() {
		return $this->national;
	}
}