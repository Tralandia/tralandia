<?php

namespace Dictionary;

use Dictionary;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 */
class Type extends \BaseEntity
{

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var string
	 * @Column(type="string")
	 */
	protected $entityName;

	/**
	 * @var string
	 * @Column(type="string")
	 */
	protected $entityAttribute;

	/**
	 * @var Collection
	 * @ManyToOne(targetEntity="Quality")
	 */
	protected $translationQualityRequirement;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $isMultitranslationRequired;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $isGenderNumberRequired;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $isLocativeRequired;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $isPositionRequired;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $isWebalizedRequired;



	/**
	 * @param string $name
	 * @return Type
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $entityName
	 * @return Type
	 */
	public function setEntityName($entityName)
	{
		$this->entityName = $entityName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getEntityName()
	{
		return $this->entityName;
	}


	/**
	 * @param string $entityAttribute
	 * @return Type
	 */
	public function setEntityAttribute($entityAttribute)
	{
		$this->entityAttribute = $entityAttribute;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getEntityAttribute()
	{
		return $this->entityAttribute;
	}


	/**
	 * @param Quality $translationQualityRequirement
	 * @return Type
	 */
	public function setTranslationQualityRequirement(Quality  $translationQualityRequirement)
	{
		$this->translationQualityRequirement = $translationQualityRequirement;
		return $this;
	}


	/**
	 * @return Quality
	 */
	public function getTranslationQualityRequirement()
	{
		return $this->translationQualityRequirement;
	}


	/**
	 * @param boolean $isMultitranslationRequired
	 * @return Type
	 */
	public function setIsMultitranslationRequired($isMultitranslationRequired)
	{
		$this->isMultitranslationRequired = $isMultitranslationRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getIsMultitranslationRequired()
	{
		return $this->isMultitranslationRequired;
	}


	/**
	 * @param boolean $isGenderNumberRequired
	 * @return Type
	 */
	public function setIsGenderNumberRequired($isGenderNumberRequired)
	{
		$this->isGenderNumberRequired = $isGenderNumberRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getIsGenderNumberRequired()
	{
		return $this->isGenderNumberRequired;
	}


	/**
	 * @param boolean $isLocativeRequired
	 * @return Type
	 */
	public function setIsLocativeRequired($isLocativeRequired)
	{
		$this->isLocativeRequired = $isLocativeRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getIsLocativeRequired()
	{
		return $this->isLocativeRequired;
	}


	/**
	 * @param boolean $isPositionRequired
	 * @return Type
	 */
	public function setIsPositionRequired($isPositionRequired)
	{
		$this->isPositionRequired = $isPositionRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getIsPositionRequired()
	{
		return $this->isPositionRequired;
	}


	/**
	 * @param boolean $isWebalizedRequired
	 * @return Type
	 */
	public function setIsWebalizedRequired($isWebalizedRequired)
	{
		$this->isWebalizedRequired = $isWebalizedRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getIsWebalizedRequired()
	{
		return $this->isWebalizedRequired;
	}

}
