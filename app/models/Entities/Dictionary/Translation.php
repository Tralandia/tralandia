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
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation2;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation3;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation4;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation5;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translation6;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized2;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized3;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized4;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized5;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translationWebalized6;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending2;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending3;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending4;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending5;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $translationPending6;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $translated;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $variations;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $variationsPending;


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
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslation2($translation2) {
		$this->translation2 = $translation2;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslation2() {
		$this->translation2 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslation2() {
		return $this->translation2;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslation3($translation3) {
		$this->translation3 = $translation3;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslation3() {
		$this->translation3 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslation3() {
		return $this->translation3;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslation4($translation4) {
		$this->translation4 = $translation4;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslation4() {
		$this->translation4 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslation4() {
		return $this->translation4;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslation5($translation5) {
		$this->translation5 = $translation5;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslation5() {
		$this->translation5 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslation5() {
		return $this->translation5;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslation6($translation6) {
		$this->translation6 = $translation6;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslation6() {
		$this->translation6 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslation6() {
		return $this->translation6;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationWebalized($translationWebalized) {
		$this->translationWebalized = $translationWebalized;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationWebalized() {
		$this->translationWebalized = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationWebalized() {
		return $this->translationWebalized;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationWebalized2($translationWebalized2) {
		$this->translationWebalized2 = $translationWebalized2;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationWebalized2() {
		$this->translationWebalized2 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationWebalized2() {
		return $this->translationWebalized2;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationWebalized3($translationWebalized3) {
		$this->translationWebalized3 = $translationWebalized3;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationWebalized3() {
		$this->translationWebalized3 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationWebalized3() {
		return $this->translationWebalized3;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationWebalized4($translationWebalized4) {
		$this->translationWebalized4 = $translationWebalized4;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationWebalized4() {
		$this->translationWebalized4 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationWebalized4() {
		return $this->translationWebalized4;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationWebalized5($translationWebalized5) {
		$this->translationWebalized5 = $translationWebalized5;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationWebalized5() {
		$this->translationWebalized5 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationWebalized5() {
		return $this->translationWebalized5;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationWebalized6($translationWebalized6) {
		$this->translationWebalized6 = $translationWebalized6;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationWebalized6() {
		$this->translationWebalized6 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationWebalized6() {
		return $this->translationWebalized6;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationPending($translationPending) {
		$this->translationPending = $translationPending;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationPending() {
		$this->translationPending = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationPending() {
		return $this->translationPending;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationPending2($translationPending2) {
		$this->translationPending2 = $translationPending2;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationPending2() {
		$this->translationPending2 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationPending2() {
		return $this->translationPending2;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationPending3($translationPending3) {
		$this->translationPending3 = $translationPending3;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationPending3() {
		$this->translationPending3 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationPending3() {
		return $this->translationPending3;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationPending4($translationPending4) {
		$this->translationPending4 = $translationPending4;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationPending4() {
		$this->translationPending4 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationPending4() {
		return $this->translationPending4;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationPending5($translationPending5) {
		$this->translationPending5 = $translationPending5;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationPending5() {
		$this->translationPending5 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationPending5() {
		return $this->translationPending5;
	}

	/**
	 * @param string
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslationPending6($translationPending6) {
		$this->translationPending6 = $translationPending6;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslationPending6() {
		$this->translationPending6 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslationPending6() {
		return $this->translationPending6;
	}

	/**
	 * @param \Nette\Datetime
	 * @return \Entities\Dictionary\Translation
	 */
	public function setTranslated(\Nette\Datetime $translated) {
		$this->translated = $translated;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Translation
	 */
	public function unsetTranslated() {
		$this->translated = NULL;

		return $this;
	}

	/**
	 * @return \Nette\Datetime|NULL
	 */
	public function getTranslated() {
		return $this->translated;
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
	 * @param json
	 * @return \Entities\Dictionary\Translation
	 */
	public function setVariationsPending($variationsPending) {
		$this->variationsPending = $variationsPending;

		return $this;
	}

	/**
	 * @return json|NULL
	 */
	public function getVariationsPending() {
		return $this->variationsPending;
	}

}