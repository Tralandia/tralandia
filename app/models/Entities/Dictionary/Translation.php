<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_translation")
 */
class Translation extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Phrase")
	 */
	protected $phrase;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Language")
	 */
	protected $language;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translation;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translation2;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translation3;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translation4;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translation5;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translation6;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $translationWebalized;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $translationWebalized2;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $translationWebalized3;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $translationWebalized4;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $translationWebalized5;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $translationWebalized6;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translationPending;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translationPending2;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translationPending3;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translationPending4;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translationPending5;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $translationPending6;

	/**
	 * @var datetime
	 * @ORM\ManyToMany(type="datetime")
	 */
	protected $translated;

	/**
	 * @var json
	 * @ORM\ManyToMany(type="json")
	 */
	protected $variations;

	/**
	 * @var json
	 * @ORM\ManyToMany(type="json")
	 */
	protected $variationsPending;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param Phrase $phrase
	 * @return Translation
	 */
	public function setPhrase(Phrase  $phrase) {
		$this->phrase = $phrase;
		return $this;
	}


	/**
	 * @return Phrase
	 */
	public function getPhrase() {
		return $this->phrase;
	}


	/**
	 * @param Language $language
	 * @return Translation
	 */
	public function setLanguage(Language  $language) {
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Language
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @param text $translation
	 * @return Translation
	 */
	public function setTranslation($translation) {
		$this->translation = $translation;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslation() {
		return $this->translation;
	}


	/**
	 * @param text $translation2
	 * @return Translation
	 */
	public function setTranslation2($translation2) {
		$this->translation2 = $translation2;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslation2() {
		return $this->translation2;
	}


	/**
	 * @param text $translation3
	 * @return Translation
	 */
	public function setTranslation3($translation3) {
		$this->translation3 = $translation3;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslation3() {
		return $this->translation3;
	}


	/**
	 * @param text $translation4
	 * @return Translation
	 */
	public function setTranslation4($translation4) {
		$this->translation4 = $translation4;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslation4() {
		return $this->translation4;
	}


	/**
	 * @param text $translation5
	 * @return Translation
	 */
	public function setTranslation5($translation5) {
		$this->translation5 = $translation5;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslation5() {
		return $this->translation5;
	}


	/**
	 * @param text $translation6
	 * @return Translation
	 */
	public function setTranslation6($translation6) {
		$this->translation6 = $translation6;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslation6() {
		return $this->translation6;
	}


	/**
	 * @param string $translationWebalized
	 * @return Translation
	 */
	public function setTranslationWebalized($translationWebalized) {
		$this->translationWebalized = $translationWebalized;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTranslationWebalized() {
		return $this->translationWebalized;
	}


	/**
	 * @param string $translationWebalized2
	 * @return Translation
	 */
	public function setTranslationWebalized2($translationWebalized2) {
		$this->translationWebalized2 = $translationWebalized2;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTranslationWebalized2() {
		return $this->translationWebalized2;
	}


	/**
	 * @param string $translationWebalized3
	 * @return Translation
	 */
	public function setTranslationWebalized3($translationWebalized3) {
		$this->translationWebalized3 = $translationWebalized3;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTranslationWebalized3() {
		return $this->translationWebalized3;
	}


	/**
	 * @param string $translationWebalized4
	 * @return Translation
	 */
	public function setTranslationWebalized4($translationWebalized4) {
		$this->translationWebalized4 = $translationWebalized4;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTranslationWebalized4() {
		return $this->translationWebalized4;
	}


	/**
	 * @param string $translationWebalized5
	 * @return Translation
	 */
	public function setTranslationWebalized5($translationWebalized5) {
		$this->translationWebalized5 = $translationWebalized5;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTranslationWebalized5() {
		return $this->translationWebalized5;
	}


	/**
	 * @param string $translationWebalized6
	 * @return Translation
	 */
	public function setTranslationWebalized6($translationWebalized6) {
		$this->translationWebalized6 = $translationWebalized6;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTranslationWebalized6() {
		return $this->translationWebalized6;
	}


	/**
	 * @param text $translationPending
	 * @return Translation
	 */
	public function setTranslationPending($translationPending) {
		$this->translationPending = $translationPending;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslationPending() {
		return $this->translationPending;
	}


	/**
	 * @param text $translationPending2
	 * @return Translation
	 */
	public function setTranslationPending2($translationPending2) {
		$this->translationPending2 = $translationPending2;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslationPending2() {
		return $this->translationPending2;
	}


	/**
	 * @param text $translationPending3
	 * @return Translation
	 */
	public function setTranslationPending3($translationPending3) {
		$this->translationPending3 = $translationPending3;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslationPending3() {
		return $this->translationPending3;
	}


	/**
	 * @param text $translationPending4
	 * @return Translation
	 */
	public function setTranslationPending4($translationPending4) {
		$this->translationPending4 = $translationPending4;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslationPending4() {
		return $this->translationPending4;
	}


	/**
	 * @param text $translationPending5
	 * @return Translation
	 */
	public function setTranslationPending5($translationPending5) {
		$this->translationPending5 = $translationPending5;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslationPending5() {
		return $this->translationPending5;
	}


	/**
	 * @param text $translationPending6
	 * @return Translation
	 */
	public function setTranslationPending6($translationPending6) {
		$this->translationPending6 = $translationPending6;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslationPending6() {
		return $this->translationPending6;
	}


	/**
	 * @param datetime $translated
	 * @return Translation
	 */
	public function setTranslated($translated) {
		$this->translated = $translated;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getTranslated() {
		return $this->translated;
	}


	/**
	 * @param json $variations
	 * @return Translation
	 */
	public function setVariations($variations) {
		$this->variations = $variations;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getVariations() {
		return $this->variations;
	}


	/**
	 * @param json $variationsPending
	 * @return Translation
	 */
	public function setVariationsPending($variationsPending) {
		$this->variationsPending = $variationsPending;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getVariationsPending() {
		return $this->variationsPending;
	}

}
