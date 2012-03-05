<?php

use Dictionary;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="language")
 */
class Language extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ManyToOne(targetEntity="Dictionary\Phrase", inversedBy="languages")
	 */
	protected $name;

	/**
	 * @var string
	 * @Column(type="string")
	 */
	protected $iso;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $supported;


	public function __construct() {

	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Language
	 */
	public function setName(Dictionary\Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param string $iso
	 * @return Language
	 */
	public function setIso($iso) {
		$this->iso = $iso;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getIso() {
		return $this->iso;
	}


	/**
	 * @param boolean $supported
	 * @return Language
	 */
	public function setSupported($supported) {
		$this->supported = $supported;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getSupported() {
		return $this->supported;
	}

}
