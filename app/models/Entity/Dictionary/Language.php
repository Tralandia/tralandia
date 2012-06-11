<?php

namespace Entity\Dictionary;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_language", indexes={@ORM\index(name="iso", columns={"iso"}), @ORM\index(name="supported", columns={"supported"})})
 * @EA\Service(name="\Service\Dictionary\Language")
 * @EA\ServiceList(name="\Service\Dictionary\LanguageList")
 * @EA\Primary(key="id", value="iso")
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
	 * @ORM\Column(type="boolean", nullable=true)
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
	protected $genders;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $plurals;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $primarySingular;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $primaryPlural;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $primaryGender;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $ppcPatterns;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 * this defines rules for autogenerating some variations, which will be done in JS. For example, locative in EN is always "in ".$singularNominative
	 */
	protected $variationDetails;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", inversedBy="languages")
	 */
	protected $locations;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="spokenLanguages")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Seo\BackLink", mappedBy="language")
	 */
	protected $backLinks;


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->backLinks = new \Doctrine\Common\Collections\ArrayCollection;
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
	 * @return \Entity\Dictionary\Language
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
		
	/**
	 * @param \Entity\Seo\BackLink
	 * @return \Entity\Dictionary\Language
	 */
	public function addBackLink(\Entity\Seo\BackLink $backLink) {
		if(!$this->backLinks->contains($backLink)) {
			$this->backLinks->add($backLink);
		}
		$backLink->setLanguage($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Seo\BackLink
	 * @return \Entity\Dictionary\Language
	 */
	public function removeBackLink(\Entity\Seo\BackLink $backLink) {
		if($this->backLinks->contains($backLink)) {
			$this->backLinks->removeElement($backLink);
		}
		$backLink->unsetLanguage();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Seo\BackLink
	 */
	public function getBackLinks() {
		return $this->backLinks;
	}
}