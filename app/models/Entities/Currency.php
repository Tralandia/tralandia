<?php

use Dictionary;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="currency")
 */
class Currency extends \BaseEntity {

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $iso;

	/**
	 * @var decimal
	 * @Column(type="decimal")
	 */
	protected $exchangeRate;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $decimalPlaces;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $rounding;


	public function __construct() {

	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Currency
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
	 * @return Currency
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
	 * @param decimal $exchangeRate
	 * @return Currency
	 */
	public function setExchangeRate($exchangeRate) {
		$this->exchangeRate = $exchangeRate;
		return $this;
	}


	/**
	 * @return decimal
	 */
	public function getExchangeRate() {
		return $this->exchangeRate;
	}


	/**
	 * @param integer $decimalPlaces
	 * @return Currency
	 */
	public function setDecimalPlaces($decimalPlaces) {
		$this->decimalPlaces = $decimalPlaces;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getDecimalPlaces() {
		return $this->decimalPlaces;
	}


	/**
	 * @param string $rounding
	 * @return Currency
	 */
	public function setRounding($rounding) {
		$this->rounding = $rounding;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getRounding() {
		return $this->rounding;
	}

}
