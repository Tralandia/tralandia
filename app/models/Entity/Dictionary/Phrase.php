<?php

namespace Entity\Dictionary;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_phrase")
 */
class Phrase extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Translation", mappedBy="phrase", cascade={"persist", "remove"})
	 */
	protected $translations;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $ready = FALSE; 

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Language", cascade={"persist"})
	 * This will be used by Locations (localities) to make sure we know the original language of the name of the locality to translate from
	 */
	protected $sourceLanguage;

	




//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->translations = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Dictionary\Translation
	 * @return \Entity\Dictionary\Phrase
	 */
	public function addTranslation(\Entity\Dictionary\Translation $translation) {
		if(!$this->translations->contains($translation)) {
			$this->translations->add($translation);
		}
		$translation->setPhrase($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Dictionary\Translation
	 * @return \Entity\Dictionary\Phrase
	 */
	public function removeTranslation(\Entity\Dictionary\Translation $translation) {
		if($this->translations->contains($translation)) {
			$this->translations->removeElement($translation);
		}
		$translation->unsetPhrase();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Dictionary\Translation
	 */
	public function getTranslations() {
		return $this->translations;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Phrase
	 */
	public function setReady($ready) {
		$this->ready = $ready;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getReady() {
		return $this->ready;
	}
		
	/**
	 * @param \Entity\Dictionary\Type
	 * @return \Entity\Dictionary\Phrase
	 */
	public function setType(\Entity\Dictionary\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Dictionary\Phrase
	 */
	public function setSourceLanguage(\Entity\Dictionary\Language $sourceLanguage) {
		$this->sourceLanguage = $sourceLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase
	 */
	public function unsetSourceLanguage() {
		$this->sourceLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getSourceLanguage() {
		return $this->sourceLanguage;
	}
}