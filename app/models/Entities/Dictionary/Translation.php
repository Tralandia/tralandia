<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_translation")
 */
class Translation extends \Entities\BaseEntity {

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

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Dictionary\Translation
	 */
	public function setPhrase(\Entities\Dictionary\Phrase $phrase) {
		$this->phrase = $phrase;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Phrase
	 */
	public function getPhrase() {
		return $this->phrase;
	}

	/**
	 * @param \Entities\Dictionary\Language
	 * @return \Entities\Dictionary\Translation
	 */
	public function setLanguage(\Entities\Dictionary\Language $language) {
		$this->language = $language;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetLanguage() {
		$this->language = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language|NULL
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslation($translation) {
		$this->translation = $translation;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
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
	 * @return \Entities\Dictionary\Translation
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
	 * @param \Nette\Datetime
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTimeTranslated(\Nette\Datetime $timeTranslated) {
		$this->timeTranslated = $timeTranslated;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTimeTranslated() {
		$this->timeTranslated = NULL;

		return $this;
	}

	/**
	 * @return \Nette\Datetime|NULL
	 */
	public function getTimeTranslated() {
		return $this->timeTranslated;
	}

	/**
	 * @param boolean
	 * @return \Entities\Dictionary\Translation
	 */
	public function setChecked($checked) {
		$this->checked = $checked;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
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