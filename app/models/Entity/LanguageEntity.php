<?php

namespace Entity;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\LanguageRepository")
 * @ORM\Table(name="language", indexes={@ORM\index(name="iso", columns={"iso"}), @ORM\index(name="supported", columns={"supported"})})
 * @EA\Primary(key="id", value="iso")
 */
class Language extends \Entity\BaseEntityDetails {

	const SUPPORTED = TRUE;
	const NOT_SUPPORTED = FALSE;
	const NOMINATIVE = 'nominative';
	const LOCATIVE = 'locative';

	const DEFAULT_SINGULAR = 0;
	const DEFAULT_PLURAL = 0;
	const DEFAULT_GENDER = 0;
	const DEFAULT_CASE = self::NOMINATIVE;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
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
	 * @EA\Json(structure="{feminine:, masculine:, neuter:}")
	 */
	protected $genders;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 * @EA\Json(structure="{polozka1: Prvá položka, polozka2: null, polocka3: [p1: null, p2: true, p3: 99, p4: Yes]}")
	 */
	protected $plurals;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 * @EA\Json(structure="{1:, 2:, 3:}")
	 */
	protected $ppcPatterns;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 * @EA\Json(structure="{1:, 2:, 3:}")
	 * this defines rules for autogenerating some variations, which will be done in JS. For example, locative in EN is always "in ".$singularNominative
	 */
	protected $variationDetails;

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

	public function getPluralsNames()
	{
		if(isset($this->plurals['names'])) {
			return $this->plurals['names'];
		}
		return $this->getDefaultPluralName();
	}

	public function getDefaultPluralName()
	{
		return array(self::DEFAULT_PLURAL => 'default');
	}

	public function getGendersNames()
	{
		if(isset($this->genders) && count($this->genders)) {
			return $this->genders;
		}
		return $this->getDefaultGenderName();
	}

	public function getDefaultGenderName()
	{
		return array(self::DEFAULT_GENDER => 'default');
	}

	public function getCasesNames()
	{
		return array(self::NOMINATIVE => 'Nominative', self::LOCATIVE => 'Locative');
	}

	public function getDefaultCaseName()
	{
		return array(self::DEFAULT_CASE => 'default');
	}

	public function getPlural($n)
	{
		if ($n === NULL) $n = 1;
		$rule = $this->getPlurals();
		eval('$plural = (int)'.$rule['rule'].';');
		return $plural;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->backLinks = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Language
	 */
	public function setName(\Entity\Phrase\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Language
	 */
	public function setIso($iso)
	{
		$this->iso = $iso;

		return $this;
	}
		
	/**
	 * @return \Entity\Language
	 */
	public function unsetIso()
	{
		$this->iso = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getIso()
	{
		return $this->iso;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Language
	 */
	public function setSupported($supported)
	{
		$this->supported = $supported;

		return $this;
	}
		
	/**
	 * @return \Entity\Language
	 */
	public function unsetSupported()
	{
		$this->supported = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getSupported()
	{
		return $this->supported;
	}
		
	/**
	 * @param string
	 * @return \Entity\Language
	 */
	public function setDefaultCollation($defaultCollation)
	{
		$this->defaultCollation = $defaultCollation;

		return $this;
	}
		
	/**
	 * @return \Entity\Language
	 */
	public function unsetDefaultCollation()
	{
		$this->defaultCollation = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDefaultCollation()
	{
		return $this->defaultCollation;
	}
		
	/**
	 * @param json
	 * @return \Entity\Language
	 */
	public function setGenders($genders)
	{
		$this->genders = $genders;

		return $this;
	}
		
	/**
	 * @return \Entity\Language
	 */
	public function unsetGenders()
	{
		$this->genders = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getGenders()
	{
		return $this->genders;
	}
		
	/**
	 * @param json
	 * @return \Entity\Language
	 */
	public function setPlurals($plurals)
	{
		$this->plurals = $plurals;

		return $this;
	}
		
	/**
	 * @return \Entity\Language
	 */
	public function unsetPlurals()
	{
		$this->plurals = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPlurals()
	{
		return $this->plurals;
	}
		
	/**
	 * @param json
	 * @return \Entity\Language
	 */
	public function setPpcPatterns($ppcPatterns)
	{
		$this->ppcPatterns = $ppcPatterns;

		return $this;
	}
		
	/**
	 * @return \Entity\Language
	 */
	public function unsetPpcPatterns()
	{
		$this->ppcPatterns = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPpcPatterns()
	{
		return $this->ppcPatterns;
	}
		
	/**
	 * @param json
	 * @return \Entity\Language
	 */
	public function setVariationDetails($variationDetails)
	{
		$this->variationDetails = $variationDetails;

		return $this;
	}
		
	/**
	 * @return \Entity\Language
	 */
	public function unsetVariationDetails()
	{
		$this->variationDetails = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getVariationDetails()
	{
		return $this->variationDetails;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Language
	 */
	public function addRental(\Entity\Rental\Rental $rental)
	{
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Language
	 */
	public function removeRental(\Entity\Rental\Rental $rental)
	{
		$this->rentals->removeElement($rental);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Rental[]
	 */
	public function getRentals()
	{
		return $this->rentals;
	}
		
	/**
	 * @param \Entity\Seo\BackLink
	 * @return \Entity\Language
	 */
	public function addBackLink(\Entity\Seo\BackLink $backLink)
	{
		if(!$this->backLinks->contains($backLink)) {
			$this->backLinks->add($backLink);
		}
		$backLink->setLanguage($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Seo\BackLink
	 * @return \Entity\Language
	 */
	public function removeBackLink(\Entity\Seo\BackLink $backLink)
	{
		$this->backLinks->removeElement($backLink);
		$backLink->unsetLanguage();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Seo\BackLink[]
	 */
	public function getBackLinks()
	{
		return $this->backLinks;
	}
}