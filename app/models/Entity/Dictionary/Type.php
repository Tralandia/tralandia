<?php

namespace Entity\Dictionary;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_type")
 */
class Type extends \Entity\BaseEntity {

	const TRANSLATION_LEVEL_PASSIVE = 0;
	const TRANSLATION_LEVEL_ACTIVE = 2;
	const TRANSLATION_LEVEL_NATIVE = 4;
	const TRANSLATION_LEVEL_MARKETING = 6;

	const REQUIRED_LANGUAGES_SUPPORTED = 'supportedLanguages';
	const REQUIRED_LANGUAGES_INCOMING = 'incomingLanguages';

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
	 * @ORM\Column(type="string")
	 * "supportedLanguages", "incomingLanguages" or list of IDs separated by ",": ",1,2,3,4,"
	 */
	protected $requiredLanguages;
	
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
	protected $multitranslationRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $genderNumberRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $locativeRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $positionRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $webalizedRequired = FALSE;

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