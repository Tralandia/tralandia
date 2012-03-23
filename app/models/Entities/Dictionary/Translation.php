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
	 * contains multitranslations, webalized versions, locative versions, everything, even contains the $translation original version
	 */
	protected $variations;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $timeTranslated;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $pendingVariations;

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
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setPendingVariations($pendingVariations) {
		$this->pendingVariations = $pendingVariations;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetPendingVariations() {
		$this->pendingVariations = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getPendingVariations() {
		return $this->pendingVariations;
	}

}