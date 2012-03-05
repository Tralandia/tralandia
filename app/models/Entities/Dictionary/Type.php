<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_type")
 */
class Type extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $entityName;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $entityAttribute;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $translationLevelRequirement;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $multitranslationRequired;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $genderNumberRequired;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $locativeRequired;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $positionRequired;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $webalizedRequired;


	public function __construct() {

	}


	/**
	 * @param string $name
	 * @return Type
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param string $entityName
	 * @return Type
	 */
	public function setEntityName($entityName) {
		$this->entityName = $entityName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getEntityName() {
		return $this->entityName;
	}


	/**
	 * @param string $entityAttribute
	 * @return Type
	 */
	public function setEntityAttribute($entityAttribute) {
		$this->entityAttribute = $entityAttribute;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getEntityAttribute() {
		return $this->entityAttribute;
	}


	/**
	 * @param Quality $translationQualityRequirement
	 * @return Type
	 */
	public function setTranslationQualityRequirement(Quality  $translationQualityRequirement) {
		$this->translationQualityRequirement = $translationQualityRequirement;
		return $this;
	}


	/**
	 * @return Quality
	 */
	public function getTranslationQualityRequirement() {
		return $this->translationQualityRequirement;
	}


	/**
	 * @param boolean $multitranslationRequired
	 * @return Type
	 */
	public function setMultitranslationRequired($multitranslationRequired) {
		$this->multitranslationRequired = $multitranslationRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getMultitranslationRequired() {
		return $this->multitranslationRequired;
	}


	/**
	 * @param boolean $genderNumberRequired
	 * @return Type
	 */
	public function setGenderNumberRequired($genderNumberRequired) {
		$this->genderNumberRequired = $genderNumberRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getGenderNumberRequired() {
		return $this->genderNumberRequired;
	}


	/**
	 * @param boolean $locativeRequired
	 * @return Type
	 */
	public function setLocativeRequired($locativeRequired) {
		$this->locativeRequired = $locativeRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getLocativeRequired() {
		return $this->locativeRequired;
	}


	/**
	 * @param boolean $positionRequired
	 * @return Type
	 */
	public function setPositionRequired($positionRequired) {
		$this->positionRequired = $positionRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getPositionRequired() {
		return $this->positionRequired;
	}


	/**
	 * @param boolean $webalizedRequired
	 * @return Type
	 */
	public function setWebalizedRequired($webalizedRequired) {
		$this->webalizedRequired = $webalizedRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getWebalizedRequired() {
		return $this->webalizedRequired;
	}

}
