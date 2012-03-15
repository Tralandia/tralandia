<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_language")
 */
class Language extends \Entities\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Phrase")
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
	 * @ORM\Column(type="json")
	 */
	protected $salutations;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $multitranslationOptions;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $genderNumberOptions;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $ppcPatterns;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", inversedBy="languages")
	 */
	protected $locations;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Rental\Rental", inversedBy="languagesSpoken")
	 */
	protected $rentals;

	
	public function __construct() {
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Dictionary\Language
	 */
	public function setName(\Entities\Dictionary\Phrase $name) {
		$this->name = $name;
		$name->setEntityId($this->getId());

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
	 * @return \Entities\Dictionary\Language
	 */
	public function unsetSupported() {
		$this->supported = NULL;

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
	 * @param \Extras\Types\Json
	 * @return \Entities\Dictionary\Language
	 */
	public function setSalutations(\Extras\Types\Json $salutations) {
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
	 * @return \Extras\Types\Json|NULL
	 */
	public function getSalutations() {
		return $this->salutations;
	}

	/**
	 * @param \Extras\Types\Json
	 * @return \Entities\Dictionary\Language
	 */
	public function setMultitranslationOptions(\Extras\Types\Json $multitranslationOptions) {
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
	 * @return \Extras\Types\Json|NULL
	 */
	public function getMultitranslationOptions() {
		return $this->multitranslationOptions;
	}

	/**
	 * @param \Extras\Types\Json
	 * @return \Entities\Dictionary\Language
	 */
	public function setGenderNumberOptions(\Extras\Types\Json $genderNumberOptions) {
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
	 * @return \Extras\Types\Json|NULL
	 */
	public function getGenderNumberOptions() {
		return $this->genderNumberOptions;
	}

	/**
	 * @param \Extras\Types\Json
	 * @return \Entities\Dictionary\Language
	 */
	public function setPpcPatterns(\Extras\Types\Json $ppcPatterns) {
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
	 * @return \Extras\Types\Json|NULL
	 */
	public function getPpcPatterns() {
		return $this->ppcPatterns;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}

}