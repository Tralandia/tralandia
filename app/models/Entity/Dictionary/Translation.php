<?php

namespace Entity\Dictionary;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_translation")
 */
class Translation extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Phrase", inversedBy="translations", cascade={"persist", "remove"})
	 */
	protected $phrase;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Language", cascade={"persist"})
	 */
	protected $language;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 * contains keys: translation, multiTranslations, webalized, locative. Even contains the $translation original version
	 */
	protected $variations;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $timeTranslated;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $checked;


	


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Dictionary\Translation
	 */
	public function setPhrase(\Entity\Dictionary\Phrase $phrase) {
		$this->phrase = $phrase;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Translation
	 */
	public function unsetPhrase() {
		$this->phrase = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getPhrase() {
		return $this->phrase;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Dictionary\Translation
	 */
	public function setLanguage(\Entity\Dictionary\Language $language) {
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Translation
	 */
	public function unsetLanguage() {
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getLanguage() {
		return $this->language;
	}
		
	/**
	 * @param string
	 * @return \Entity\Dictionary\Translation
	 */
	public function setTranslation($translation) {
		$this->translation = $translation;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Translation
	 */
	public function unsetTranslation() {
		$this->translation = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getTranslation() {
		return $this->translation;
	}
		
	/**
	 * @param json
	 * @return \Entity\Dictionary\Translation
	 */
	public function setVariations($variations) {
		$this->variations = $variations;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getVariations() {
		return $this->variations;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Dictionary\Translation
	 */
	public function setTimeTranslated(\DateTime $timeTranslated) {
		$this->timeTranslated = $timeTranslated;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Translation
	 */
	public function unsetTimeTranslated() {
		$this->timeTranslated = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getTimeTranslated() {
		return $this->timeTranslated;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Dictionary\Translation
	 */
	public function setChecked($checked) {
		$this->checked = $checked;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Translation
	 */
	public function unsetChecked() {
		$this->checked = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getChecked() {
		return $this->checked;
	}
}