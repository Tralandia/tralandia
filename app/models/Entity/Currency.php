<?php

namespace Entity;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="currency")
 */
class Currency extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
     * @UIControl(type="text")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
     * @UIControl(type="text")
	 */
	protected $iso;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal", nullable=true)
     * @UIControl(type="text")
	 */
	protected $exchangeRate;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
     * @UIControl(type="text")
	 */
	protected $decimalPlaces;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
     * @UIControl(type="text")
	 */
	protected $rounding;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Country", inversedBy="currencies")
	 */
	protected $countries;

	public function __construct() {
        parent::__construct();
 
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection;
    }
 
 
    /**
     * @param Entity\Dictionary\Phrase
     * @return Entity\Currency
     */
    public function setName(\Entity\Dictionary\Phrase $name) {
        $this->name = $name;
 
        return $this;
    }
 
 
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection of Entity\Dictionary\Phrase
     */
    public function getName() {
        return $this->name;
    }
 
 
    /**
     * @param string
     * @return Entity\Currency
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
     * @return Entity\Currency
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
     * @return Entity\Currency
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
     * @return Entity\Currency
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
     * @return \Doctrine\Common\Collections\ArrayCollection of Entity\Location\Country
     */
    public function getCountries() {
        return $this->countries;
    }

}