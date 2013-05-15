<?php

namespace Entity\Phrase;

use Doctrine\ORM\Mapping as ORM;
use Entity\Language;
use	Extras\Annotation as EA;
use Nette\Utils\Strings;
use Nette\Utils\Arrays;

/**
 * @ORM\Entity(repositoryClass="Repository\Phrase\PhraseRepository")
 * @ORM\Table(name="phrase", indexes={@ORM\index(name="ready", columns={"ready"})})
 * @EA\Primary(key="id", value="translations")
 */
class Phrase extends \Entity\BaseEntityDetails {

	const REQUESTED = 1;
	const CENTRAL = 5;
	const SOURCE = 10;

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
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $corrected = FALSE;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="\Entity\Language")
	 * This will be used by Locations (localities) to make sure we know the original language of the name of the locality to translate from
	 */
	protected $sourceLanguage;


	/**
	 * @param \Entity\Language $language
	 * @param string|null $translationText
	 *
	 * @return Translation
	 * @throws \Nette\InvalidArgumentException
	 */
	public function createTranslation(\Entity\Language $language, $translationText = NULL) {
		$type = $this->getType();
		if(!$type instanceof \Entity\Phrase\Type) {
			throw new \Nette\InvalidArgumentException('Set phrase type before creating translations.');
		}
		$translation = new \Entity\Phrase\Translation;

		$this->addTranslation($translation);
		$translation->setLanguage($language);
		$translation->setVariations($this->getTranslationVariationsMatrix($language));
		if($translationText !== NULL) $translation->setTranslation($translationText);

		return $translation;
	}

	/**
	 * @param \Entity\Language $language
	 *
	 * @return array
	 */
	public function getTranslationVariationsMatrix(\Entity\Language $language) {
		if($this->getType()->getPluralVariationsRequired()) {
			$plurals = $language->getPluralsNames();
		} else {
			$plurals = $language->getDefaultPluralName();
		}

		if($this->getType()->getGenderVariationsRequired()) {
			$genders = $language->getGendersNames();
		} else {
			$genders = $language->getDefaultGenderName();
		}

		if($this->getType()->getLocativesRequired()) {
			$cases = $language->getCasesNames();
		} else {
			$cases = $language->getDefaultCaseName();
		}

		$matrix = array();
		foreach ($plurals as $pluralKey => $pluralValue) {
			foreach ($genders as $genderKey => $genderValue) {
				foreach ($cases as $caseKey => $caseValue) {
					$matrix[$pluralKey][$genderKey][$caseKey] = NULL;
				}
			}
		}

		return $matrix;
	}

	/**
	 * Vrati translation-y v ziadanom, centralom a source jazyku, ak existuju
	 *
	 * @param \Entity\Language $language
	 * @return \Entity\Phrase\Translation[]
	 */
	public function getMainTranslations(\Entity\Language $language = NULL) {
		$t = array();

		foreach ($this->getTranslations() as $value) {
			if ($language && $value->getLanguage()->getId() == $language->getId()) {
				$t[self::REQUESTED] = $value;
			}

			if ($value->getLanguage()->getId() == CENTRAL_LANGUAGE) {
				$t[self::CENTRAL] = $value;
			}

			if ($this->sourceLanguage && $value->getLanguage()->getId() == $this->getSourceLanguage()->getId()) {
				$t[self::SOURCE] = $value;
			}
		}

		ksort($t);

		return $t;
	}


	public function getWordsCount(Language $language)
	{
		$sourceTranslationText = $this->getSourceTranslationText();
		$defaultVariationWordsCount = str_word_count($sourceTranslationText);

		$variationsCount = $language->getVariationsCount();

		return $defaultVariationWordsCount * $variationsCount;
	}


	/**
	 * @param \Entity\Language $language
	 * @param bool $loose
	 *
	 * @return \Entity\Phrase\Translation|null
	 */
	public function getTranslation(\Entity\Language $language, $loose = FALSE) {
		$t = $this->getMainTranslations($language);
		if ($loose) {
			$t = array_filter($t);
			return reset($t);
		} else {
			return (array_key_exists(self::REQUESTED, $t) ? $t[self::REQUESTED] : NULL);
		}
	}

	// public function getTranslation(\Entity\Language $language) {
	// 	foreach ($this->getTranslations() as $value) {
	// 		if ($value->getLanguage()->getId() == $language->getId()) {
	// 			return $value;
	// 		}
	// 	}
	// }

	public function getTranslationText(\Entity\Language $language, $loose = FALSE) {
		$translation = $this->getMainTranslations($language);
		$text = '';
		if ($loose) {
			foreach ($translation as $value) {
				if (strlen((string) $value)) {
					$text = (string) $value;
					break;
				}
			}
		} else {
			$text = (string) Arrays::get($translation, self::REQUESTED, $text);
		}

		if(!strlen($text)) $text = '{~'.$this->getId().'|'.$language->getIso().'~}';
		return $text;
	}

	public function getSourceTranslation() {
		foreach ($this->getTranslations() as $value) {
			if ($this->getSourceLanguage() && $value->getLanguage()->getId() == $this->getSourceLanguage()->getId()) {
				return $value;
			}
		}
		return FALSE;
	}

	public function getSourceTranslationText() {
		$translation = $this->getSourceTranslation();
		return $translation ? $translation->getTranslation() : NULL;
	}


	public function hasTranslation($language) {
		return $this->getTranslation($language) instanceof \Entity\Phrase\Translation;
	}

	public function hasTranslationText($language) {
		$translation = $this->getTranslation($language);
		return $translation && strlen($translation->getTranslation());
	}

	public function getValidTranslationsCount() {
		$c = 0;
		foreach ($this->getTranslations() as $key => $value) {
			if (Strings::length($value->translation) > 0) $c++;
		}

		return $c;
	}

	public function hasCentralTranslationText() {
		return Strings::length($this->getCentralTranslationText()) > 0;
	}


	/**
	 * @return NULL|string
	 */
	public function getCentralTranslationText() {
		return $this->getCentralTranslation()->getTranslation();
	}


	/**
	 * @return Translation|null
	 */
	public function getCentralTranslation() {
		$mainTranslations = $this->getMainTranslations();
		return array_key_exists(self::CENTRAL, $mainTranslations) ? $mainTranslations[self::CENTRAL] : NULL;
	}


	/**
	 * @return bool
	 */
	public function hasSourceTranslation() {
		$mainTranslations = $this->getMainTranslations();
		return Strings::length($mainTranslations[self::SOURCE]->translation) > 0;
	}


	/**
	 * @param \Entity\Language $language
	 * @param $value
	 *
	 * @return $this
	 */
	public function setTranslationText(\Entity\Language $language, $value) {
		$this->getTranslation($language)->translation = $value;
		return $this;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->translations = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Translation
	 * @return \Entity\Phrase\Phrase
	 */
	public function addTranslation(\Entity\Phrase\Translation $translation)
	{
		if(!$this->translations->contains($translation)) {
			$this->translations->add($translation);
		}
		$translation->setPhrase($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Phrase\Translation
	 * @return \Entity\Phrase\Phrase
	 */
	public function removeTranslation(\Entity\Phrase\Translation $translation)
	{
		$this->translations->removeElement($translation);
		$translation->unsetPhrase();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Phrase\Translation[]
	 */
	public function getTranslations()
	{
		return $this->translations;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Phrase
	 */
	public function setReady($ready)
	{
		$this->ready = $ready;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getReady()
	{
		return $this->ready;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Phrase
	 */
	public function setCorrected($corrected)
	{
		$this->corrected = $corrected;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getCorrected()
	{
		return $this->corrected;
	}
		
	/**
	 * @param \Entity\Phrase\Type
	 * @return \Entity\Phrase\Phrase
	 */
	public function setType(\Entity\Phrase\Type $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Type|NULL
	 */
	public function getType()
	{
		return $this->type;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Phrase\Phrase
	 */
	public function setSourceLanguage(\Entity\Language $sourceLanguage)
	{
		$this->sourceLanguage = $sourceLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase
	 */
	public function unsetSourceLanguage()
	{
		$this->sourceLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getSourceLanguage()
	{
		return $this->sourceLanguage;
	}
}