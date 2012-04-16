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
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", inversedBy="currencies")
	 */
	protected $locations;


    




//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Currency
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Currency
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
	 * @param decimal
	 * @return \Entity\Currency
	 */
	public function setExchangeRate($exchangeRate) {
		$this->exchangeRate = $exchangeRate;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency
	 */
	public function unsetExchangeRate() {
		$this->exchangeRate = NULL;

		return $this;
	}
		
	/**
	 * @return decimal|NULL
	 */
	public function getExchangeRate() {
		return $this->exchangeRate;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Currency
	 */
	public function setDecimalPlaces($decimalPlaces) {
		$this->decimalPlaces = $decimalPlaces;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency
	 */
	public function unsetDecimalPlaces() {
		$this->decimalPlaces = NULL;

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
	 * @return \Entity\Currency
	 */
	public function setRounding($rounding) {
		$this->rounding = $rounding;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency
	 */
	public function unsetRounding() {
		$this->rounding = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getRounding() {
		return $this->rounding;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Currency
	 */
	public function addLocation(\Entity\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}
}