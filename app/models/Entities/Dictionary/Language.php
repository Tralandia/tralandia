<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_language")
 */
class Language extends \Entities\BaseEntityDetails {

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
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Country", inversedBy="languages")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Rental\Rental", inversedBy="languagesSpoken")
	 */
	protected $rentals;

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Dictionary\Language
	 */
	public function setName(\Entities\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Language
	 */
	public function setIso($iso) {
		$this->iso = $iso;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language
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
	 * @return \Entities\Dictionary\Language
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
	 * @return \Entities\Dictionary\Language
	 */
	public function setDefaultCollation($defaultCollation) {
		$this->defaultCollation = $defaultCollation;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language
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
	 * @return \Entities\Dictionary\Language
	 */
	public function setSalutations($salutations) {
		$this->salutations = $salutations;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language
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
	 * @return \Entities\Dictionary\Language
	 */
	public function setMultitranslationOptions($multitranslationOptions) {
		$this->multitranslationOptions = $multitranslationOptions;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language
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
	 * @return \Entities\Dictionary\Language
	 */
	public function setGenderNumberOptions($genderNumberOptions) {
		$this->genderNumberOptions = $genderNumberOptions;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language
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
	 * @return \Entities\Dictionary\Language
	 */
	public function setPpcPatterns($ppcPatterns) {
		$this->ppcPatterns = $ppcPatterns;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language
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
	 * @param \Entities\Location\Location
	 * @return \Entities\Dictionary\Language
	 */
	public function addLocation(\Entities\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->addLanguage($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}

	/**
	 * @param \Entities\Rental\Rental
	 * @return \Entities\Dictionary\Language
	 */
	public function addRental(\Entities\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}
		$rental->addLanguagesSpoken($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}

}