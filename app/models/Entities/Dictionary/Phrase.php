<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_phrase")
 */
class Phrase extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Translation")
	 */
	protected $translations;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $ready;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Language")
	 */
	protected $languages;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Type")
	 */
	protected $type;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $entityId;


	public function __construct() {

	}


	/**
	 * @param Translation $translations
	 * @return Phrase
	 */
	public function setTranslations(Translation  $translations) {
		$this->translations = $translations;
		return $this;
	}


	/**
	 * @return Translation
	 */
	public function getTranslations() {
		return $this->translations;
	}


	/**
	 * @param boolean $ready
	 * @return Phrase
	 */
	public function setReady($ready) {
		$this->ready = $ready;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getReady() {
		return $this->ready;
	}


	/**
	 * @param Language $languages
	 * @return Phrase
	 */
	public function setLanguages(Language  $languages) {
		$this->languages = $languages;
		return $this;
	}


	/**
	 * @return Language
	 */
	public function getLanguages() {
		return $this->languages;
	}


	/**
	 * @param Type $type
	 * @return Phrase
	 */
	public function setType(Type  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param integer $entityId
	 * @return Phrase
	 */
	public function setEntityId($entityId) {
		$this->entityId = $entityId;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getEntityId() {
		return $this->entityId;
	}

}
