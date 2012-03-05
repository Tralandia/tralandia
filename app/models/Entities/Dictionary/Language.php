<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_language")
 */
class Language extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Phrase")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $iso;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $supported;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $defaultCollation;

	/**
	 * @var json
	 * @ORM\ManyToMany(type="json")
	 */
	protected $salutations;

	/**
	 * @var json
	 * @ORM\ManyToMany(type="json")
	 */
	protected $multitranslationOptions;

	/**
	 * @var json
	 * @ORM\ManyToMany(type="json")
	 */
	protected $genderNumberOptions;

	/**
	 * @var json
	 * @ORM\ManyToMany(type="json")
	 */
	protected $ppcPatterns;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param Phrase $name
	 * @return Language
	 */
	public function setName(Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Phrase
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


	/**
	 * @param string $defaultCollation
	 * @return Language
	 */
	public function setDefaultCollation($defaultCollation) {
		$this->defaultCollation = $defaultCollation;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getDefaultCollation() {
		return $this->defaultCollation;
	}


	/**
	 * @param json $salutations
	 * @return Language
	 */
	public function setSalutations($salutations) {
		$this->salutations = $salutations;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getSalutations() {
		return $this->salutations;
	}


	/**
	 * @param json $multitranslationOptions
	 * @return Language
	 */
	public function setMultitranslationOptions($multitranslationOptions) {
		$this->multitranslationOptions = $multitranslationOptions;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getMultitranslationOptions() {
		return $this->multitranslationOptions;
	}


	/**
	 * @param json $genderNumberOptions
	 * @return Language
	 */
	public function setGenderNumberOptions($genderNumberOptions) {
		$this->genderNumberOptions = $genderNumberOptions;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getGenderNumberOptions() {
		return $this->genderNumberOptions;
	}


	/**
	 * @param json $ppcPatterns
	 * @return Language
	 */
	public function setPpcPatterns($ppcPatterns) {
		$this->ppcPatterns = $ppcPatterns;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getPpcPatterns() {
		return $this->ppcPatterns;
	}

}
