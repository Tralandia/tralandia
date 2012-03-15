<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_type")
 */
class Type extends \Entities\BaseEntity {

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
	protected $translationLevelRequirement;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $multitranslationRequired;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $genderNumberRequired;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $locativeRequired;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $positionRequired;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $webalizedRequired;

	
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Type
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
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
	 * @return \Entities\Dictionary\Type
	 */
	public function setEntityName($entityName) {
		$this->entityName = $entityName;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
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
	 * @return \Entities\Dictionary\Type
	 */
	public function setEntityAttribute($entityAttribute) {
		$this->entityAttribute = $entityAttribute;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
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
	 * @return \Entities\Dictionary\Type
	 */
	public function setTranslationLevelRequirement($translationLevelRequirement) {
		$this->translationLevelRequirement = $translationLevelRequirement;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
	 */
	public function unsetTranslationLevelRequirement() {
		$this->translationLevelRequirement = NULL;

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
	 * @return \Entities\Dictionary\Type
	 */
	public function setMultitranslationRequired($multitranslationRequired) {
		$this->multitranslationRequired = $multitranslationRequired;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
	 */
	public function unsetMultitranslationRequired() {
		$this->multitranslationRequired = NULL;

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
	 * @return \Entities\Dictionary\Type
	 */
	public function setGenderNumberRequired($genderNumberRequired) {
		$this->genderNumberRequired = $genderNumberRequired;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
	 */
	public function unsetGenderNumberRequired() {
		$this->genderNumberRequired = NULL;

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
	 * @return \Entities\Dictionary\Type
	 */
	public function setLocativeRequired($locativeRequired) {
		$this->locativeRequired = $locativeRequired;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
	 */
	public function unsetLocativeRequired() {
		$this->locativeRequired = NULL;

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
	 * @return \Entities\Dictionary\Type
	 */
	public function setPositionRequired($positionRequired) {
		$this->positionRequired = $positionRequired;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
	 */
	public function unsetPositionRequired() {
		$this->positionRequired = NULL;

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
	 * @return \Entities\Dictionary\Type
	 */
	public function setWebalizedRequired($webalizedRequired) {
		$this->webalizedRequired = $webalizedRequired;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type
	 */
	public function unsetWebalizedRequired() {
		$this->webalizedRequired = NULL;

		return $this;
	}

	/**
	 * @return boolean|NULL
	 */
	public function getWebalizedRequired() {
		return $this->webalizedRequired;
	}

}