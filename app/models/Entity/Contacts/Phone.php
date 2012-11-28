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
	 * @ORM\Column(type="string", length=20)
	 */
	protected $phone;
		
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