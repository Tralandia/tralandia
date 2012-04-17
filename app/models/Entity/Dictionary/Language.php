<?php

namespace Entity\Dictionary;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_language")
 */
class Language extends \Entity\BaseEntityDetails {

	const SUPPORTED = TRUE;
	const NOT_SUPPORTED = FALSE;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iso;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $supported;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $defaultCollation;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $salutations;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $multitranslationOptions;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $genderNumberOptions;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $ppcPatterns;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", inversedBy="languages")
	 */
	protected $locations;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="languagesSpoken")
	 */
	protected $rentals;

	





//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Dictionary\Language
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
	 * @return \Entity\Dictionary\Language
	 */
	public function setIso($iso) {
		$this->iso = $iso;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language
	 */
	public function unsetIso() {
		$this->iso = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getIso() {
		return $this->iso;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Language
	 */
	public function setSupported($supported) {
		$this->supported = $supported;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getSupported() {
		return $this->supported;
	}
		
	/**
	 * @param string
	 * @return \Entity\Dictionary\Language
	 */
	public function setDefaultCollation($defaultCollation) {
		$this->defaultCollation = $defaultCollation;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language
	 */
	public function unsetDefaultCollation() {
		$this->defaultCollation = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDefaultCollation() {
		return $this->defaultCollation;
	}
		
	/**
	 * @param json
	 * @return \Entity\Dictionary\Language
	 */
	public function setSalutations($salutations) {
		$this->salutations = $salutations;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language
	 */
	public function unsetSalutations() {
		$this->salutations = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getSalutations() {
		return $this->salutations;
	}
		
	/**
	 * @param json
	 * @return \Entity\Dictionary\Language
	 */
	public function setMultitranslationOptions($multitranslationOptions) {
		$this->multitranslationOptions = $multitranslationOptions;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language
	 */
	public function unsetMultitranslationOptions() {
		$this->multitranslationOptions = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getMultitranslationOptions() {
		return $this->multitranslationOptions;
	}
		
	/**
	 * @param json
	 * @return \Entity\Dictionary\Language
	 */
	public function setGenderNumberOptions($genderNumberOptions) {
		$this->genderNumberOptions = $genderNumberOptions;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language
	 */
	public function unsetGenderNumberOptions() {
		$this->genderNumberOptions = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getGenderNumberOptions() {
		return $this->genderNumberOptions;
	}
		
	/**
	 * @param json
	 * @return \Entity\Dictionary\Language
	 */
	public function setPpcPatterns($ppcPatterns) {
		$this->ppcPatterns = $ppcPatterns;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language
	 */
	public function unsetPpcPatterns() {
		$this->ppcPatterns = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPpcPatterns() {
		return $this->ppcPatterns;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Dictionary\Language
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
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Dictionary\Language
	 */
	public function addRental(\Entity\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}
}