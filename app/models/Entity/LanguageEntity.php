<?php

namespace Entity;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use Entity\User\User;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity
 * @ORM\Table(name="language", indexes={
 * 		@ORM\index(name="iso", columns={"iso"}),
 * 		@ORM\index(name="live", columns={"live"}),
 * 		@ORM\index(name="supported", columns={"supported"})
 * })
 *
 */
class Language extends \Entity\BaseEntityDetails {

	const SUPPORTED = TRUE;
	const NOT_SUPPORTED = FALSE;
	const LIVE = TRUE;
	const NOT_LIVE = FALSE;
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
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $live;

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
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $translator;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $translationPrice;

	public function getPluralsNames()
	{
		if(isset($this->plurals['names'])) {
			return $this->plurals['names'];
		}
		return $this->getDefaultPluralName();
	}

	public function getPluralName($plural)
	{
		if(isset($this->plurals['names'][$plural])) {
			return $this->plurals['names'][$plural];
		}
		return NULL;
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
		if ($n === NULL) return self::DEFAULT_PLURAL;
		$rule = $this->getPlurals();
		eval('$plural = (int)'.$rule['rule'].';');
		return $plural;
	}

	public function hasTranslator()
	{
		return $this->translator instanceof User;
	}

	public function getTranslationPriceForWords($wordsCount)
	{
		return round($wordsCount * $this->getTranslationPrice(), 2);
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();

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
	 * @param boolean
	 * @return \Entity\Language
	 */
	public function setLive($live)
	{
		$this->live = $live;

		return $this;
	}

	/**
	 * @return \Entity\Language
	 */
	public function unsetLive()
	{
		$this->live = NULL;

		return $this;
	}

	/**
	 * @return boolean|NULL
	 */
	public function getLive()
	{
		return $this->live;
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
	 * @param \Entity\User\User
	 * @return \Entity\Language
	 */
	public function setTranslator(\Entity\User\User $translator)
	{
		$this->translator = $translator;

		return $this;
	}

	/**
	 * @return \Entity\Language
	 */
	public function unsetTranslator()
	{
		$this->translator = NULL;

		return $this;
	}

	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getTranslator()
	{
		return $this->translator;
	}

	/**
	 * @param boolean
	 * @return \Entity\Language
	 */
	public function setTranslationPrice($translationPrice)
	{
		$this->translationPrice = $translationPrice;

		return $this;
	}

	/**
	 * @return \Entity\Language
	 */
	public function unsetTranslationPrice()
	{
		$this->translationPrice = NULL;

		return $this;
	}

	/**
	 * @return boolean|NULL
	 */
	public function getTranslationPrice()
	{
		return $this->translationPrice;
	}
}
