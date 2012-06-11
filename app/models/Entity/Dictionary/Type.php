<?php

namespace Entity\Dictionary;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_type", indexes={@ORM\index(name="entityName", columns={"entityName"}), @ORM\index(name="entityAttribute", columns={"entityAttribute"}), @ORM\index(name="translationLevelRequirement", columns={"translationLevelRequirement"}), @ORM\index(name="pluralsRequired", columns={"pluralsRequired"}), @ORM\index(name="genderVariationsRequired", columns={"genderVariationsRequired"}), @ORM\index(name="locativesRequired", columns={"locativesRequired"}), @ORM\index(name="positionRequired", columns={"positionRequired"}), @ORM\index(name="checkingRequired", columns={"checkingRequired"})})
 * @EA\Service(name="\Service\Dictionary\Type")
 * @EA\ServiceList(name="\Service\Dictionary\TypeList")
 * @EA\Primary(key="id", value="name")
 */
class Type extends \Entity\BaseEntity {

	const TRANSLATION_LEVEL_PASSIVE = 0;
	const TRANSLATION_LEVEL_ACTIVE = 2;
	const TRANSLATION_LEVEL_NATIVE = 4;
	const TRANSLATION_LEVEL_MARKETING = 6;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $entityName;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $entityAttribute;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $translationLevelRequirement = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $pluralsRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * whether we need to know the gender of this word / translation, for example "chata" is feminine
	 */
	protected $genderRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * whether we need the variations of expression based on gender, for example "lacny" could be "lacne" etc.
	 */
	protected $genderVariationsRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $locativesRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $positionRequired = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $helpForTranslator;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $checkingRequired;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * in EUR
	 */
	protected $monthlyBudget = 0;


	public function isSimple() {
		return !$this->pluralsRequired && !$this->genderVariationsRequired && !$this->locativesRequired && !$this->positionRequired;
	}
	

//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Dictionary\Type
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Type
	 */
	public function unsetName() {
		$this->name = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Dictionary\Type
	 */
	public function setEntityName($entityName) {
		$this->entityName = $entityName;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Type
	 */
	public function unsetEntityName() {
		$this->entityName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getEntityName() {
		return $this->entityName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Dictionary\Type
	 */
	public function setRequiredLanguages($requiredLanguages) {
		$this->requiredLanguages = $requiredLanguages;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getRequiredLanguages() {
		return $this->requiredLanguages;
	}
		
	/**
	 * @param string
	 * @return \Entity\Dictionary\Type
	 */
	public function setEntityAttribute($entityAttribute) {
		$this->entityAttribute = $entityAttribute;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Type
	 */
	public function unsetEntityAttribute() {
		$this->entityAttribute = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getEntityAttribute() {
		return $this->entityAttribute;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Dictionary\Type
	 */
	public function setTranslationLevelRequirement($translationLevelRequirement) {
		$this->translationLevelRequirement = $translationLevelRequirement;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getTranslationLevelRequirement() {
		return $this->translationLevelRequirement;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Type
	 */
	public function setMultitranslationRequired($multitranslationRequired) {
		$this->multitranslationRequired = $multitranslationRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getMultitranslationRequired() {
		return $this->multitranslationRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Type
	 */
	public function setGenderNumberRequired($genderNumberRequired) {
		$this->genderNumberRequired = $genderNumberRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getGenderNumberRequired() {
		return $this->genderNumberRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Type
	 */
	public function setLocativeRequired($locativeRequired) {
		$this->locativeRequired = $locativeRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getLocativeRequired() {
		return $this->locativeRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Type
	 */
	public function setPositionRequired($positionRequired) {
		$this->positionRequired = $positionRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getPositionRequired() {
		return $this->positionRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Type
	 */
	public function setWebalizedRequired($webalizedRequired) {
		$this->webalizedRequired = $webalizedRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getWebalizedRequired() {
		return $this->webalizedRequired;
	}
		
	/**
	 * @param string
	 * @return \Entity\Dictionary\Type
	 */
	public function setHelpForTranslator($helpForTranslator) {
		$this->helpForTranslator = $helpForTranslator;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Type
	 */
	public function unsetHelpForTranslator() {
		$this->helpForTranslator = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getHelpForTranslator() {
		return $this->helpForTranslator;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Type
	 */
	public function setCheckingRequired($checkingRequired) {
		$this->checkingRequired = $checkingRequired;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Type
	 */
	public function unsetCheckingRequired() {
		$this->checkingRequired = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getCheckingRequired() {
		return $this->checkingRequired;
	}
}