<?php

namespace Dictionary;

use Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @Entity()
 * @Table(name="DictionaryPhrase")
 */
class Phrase extends \BaseEntityDetails
{

	/**
	 * @var Collection
	 * @OneToMany(targetEntity="Translation", mappedBy="phrase")
	 */
	protected $translations;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $ready;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="\Language")
	 */
	protected $languages;

	/**
	 * @var Collection
	 * @ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $entityId;


	public function __construct() {
		$this->translations = new ArrayCollection();
		$this->languages = new ArrayCollection();
	}


	/**
	 * @param Translation $translations
	 * @return Phrase
	 */
	public function setTranslations(Translation  $translations)
	{
		$this->translations = $translations;
		return $this;
	}


	/**
	 * @return Translation
	 */
	public function getTranslations()
	{
		return $this->translations;
	}


	/**
	 * @param boolean $ready
	 * @return Phrase
	 */
	public function setReady($ready)
	{
		$this->ready = $ready;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getReady()
	{
		return $this->ready;
	}


	/**
	 * @param Language $languages
	 * @return Phrase
	 */
	public function setLanguages(\Language  $languages)
	{
		$this->languages = $languages;
		return $this;
	}


	/**
	 * @return Language
	 */
	// public function getLanguages()
	// {
	// 	return $this->languages;
	// }

	public function addLanguage(\Language  $language)
	{
		if(!$this->languages->contains($language)) {
			$this->languages->add($language);
		}

		return $this;
	}

	public function removeLanguage(\Language  $language)
	{
		if($this->languages->contains($language)) {
			$this->languages->removeElement($language);
		}

		return $this;
	}

	public function getLanguages() {
		return $this->languages->toArray();
	}

	/**
	 * @param Type $type
	 * @return Phrase
	 */
	public function setType(Type  $type)
	{
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @param integer $entityId
	 * @return Phrase
	 */
	public function setEntityId($entityId)
	{
		$this->entityId = $entityId;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getEntityId()
	{
		return $this->entityId;
	}

}
