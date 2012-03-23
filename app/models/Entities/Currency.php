<?php

namespace Entities;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="currency")
 */
class Currency extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $iso;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal", nullable=true)
	 */
	protected $exchangeRate;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $decimalPlaces;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $rounding;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Country", inversedBy="currencies")
	 */
	protected $countries;

	public function __construct() {
        parent::__construct();
 
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection;
    }
 
 
    /**
     * @param Entities\Dictionary\Phrase
     * @return Entities\Currency
     */
    public function setName(\Entities\Dictionary\Phrase $name) {
        $this->name = $name;
 
        return $this;
    }
 
 
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection of Entities\Dictionary\Phrase
     */
    public function getName() {
        return $this->name;
    }
 
 
    /**
     * @param string
     * @return Entities\Currency
     */
    public function setIso($iso) {
        $this->iso = $iso;
 
        return $this;
    }
 
 
    /**
     * @return string|NULL
     */
    public function getIso() {
        return $this->iso;
    }
 
 
    /**
     * @param \Extras\Types\Decimal
     * @return Entities\Currency
     */
    public function setExchangeRate($exchangeRate) {
        $this->exchangeRate = $exchangeRate;
 
        return $this;
    }
 
 
    /**
     * @return \Extras\Types\Decimal|NULL
     */
    public function getExchangeRate() {
        return $this->exchangeRate;
    }
 
 
    /**
     * @param integer
     * @return Entities\Currency
     */
    public function setDecimalPlaces($decimalPlaces) {
        $this->decimalPlaces = $decimalPlaces;
 
        return $this;
    }
 
 
    /**
     * @return integer|NULL
     */
    public function getDecimalPlaces() {
        return $this->decimalPlaces;
    }
 
 
    /**
     * @param string
     * @return Entities\Currency
     */
    public function setRounding($rounding) {
        $this->rounding = $rounding;
 
        return $this;
    }
 
 
    /**
     * @return string|NULL
     */
    public function getRounding() {
        return $this->rounding;
    }
 
 
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection of Entities\Location\Country
     */
    public function getCountries() {
        return $this->countries;
    }

}