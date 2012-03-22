<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_phrase")
 */
class Phrase extends \Entities\BaseEntityDetails {

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

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->translations = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Dictionary\Translation
	 * @return \Entities\Dictionary\Phrase
	 */
	public function addTranslation(\Entities\Dictionary\Translation $translation) {
		if(!$this->translations->contains($translation)) {
			$this->translations->add($translation);
		}
		$translation->setPhrase($this);

		return $this;
	}

	/**
	 * @param \Entities\Dictionary\Translation
	 * @return \Entities\Dictionary\Phrase
	 */
	public function removeTranslation(\Entities\Dictionary\Translation $translation) {
		if($this->translations->contains($translation)) {
			$this->translations->removeElement($translation);
		}
		$translation->unsetPhrase();

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Translation
	 */
	public function getTranslations() {
		return $this->translations;
	}

	/**
	 * @param boolean
	 * @return \Entities\Dictionary\Phrase
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
	 * @param \Entities\Dictionary\Type
	 * @return \Entities\Dictionary\Phrase
	 */
	public function setType(\Entities\Dictionary\Type $type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Phrase
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}

}