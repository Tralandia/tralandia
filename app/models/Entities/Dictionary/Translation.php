<?php

namespace Dictionary;

use Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @Entity()
 */
class Translation extends \BaseEntity
{

	/**
	 * @var Collection
	 * @ManyToOne(targetEntity="Phrase")
	 */
	protected $phrase;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="\Language")
	 */
	protected $language;

	/**
	 * @var text
	 * @Column(type="text")
	 */
	protected $translation;

	/**
	 * @var text
	 * @Column(type="text")
	 */
	protected $translation2;


	/**
	 * @var string
	 * @Column(type="string")
	 */
	protected $translationWebalized;

	/**
	 * @var string
	 * @Column(type="string")
	 */
	protected $translationWebalized2;


	/**
	 * @var text
	 * @Column(type="text")
	 */
	protected $translationPending;

	/**
	 * @var text
	 * @Column(type="text")
	 */
	protected $translationPending2;


	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $translated;



	/**
	 * @param Phrase $phrase
	 * @return Translation
	 */
	public function setPhrase(Phrase  $phrase)
	{
		$this->phrase = $phrase;
		return $this;
	}


	/**
	 * @return Phrase
	 */
	public function getPhrase()
	{
		return $this->phrase;
	}


	/**
	 * @param Language $language
	 * @return Translation
	 */
	public function setLanguage(Language  $language)
	{
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Language
	 */
	public function getLanguage()
	{
		return $this->language;
	}


	/**
	 * @param text $translation
	 * @return Translation
	 */
	public function setTranslation($translation)
	{
		$this->translation = $translation;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslation()
	{
		return $this->translation;
	}


	/**
	 * @param text $translation2
	 * @return Translation
	 */
	public function setTranslation2($translation2)
	{
		$this->translation2 = $translation2;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslation2()
	{
		return $this->translation2;
	}


	/**
	 * @param string $translationWebalized
	 * @return Translation
	 */
	public function setTranslationWebalized($translationWebalized)
	{
		$this->translationWebalized = $translationWebalized;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTranslationWebalized()
	{
		return $this->translationWebalized;
	}


	/**
	 * @param string $translationWebalized2
	 * @return Translation
	 */
	public function setTranslationWebalized2($translationWebalized2)
	{
		$this->translationWebalized2 = $translationWebalized2;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTranslationWebalized2()
	{
		return $this->translationWebalized2;
	}


	/**
	 * @param text $translationPending
	 * @return Translation
	 */
	public function setTranslationPending($translationPending)
	{
		$this->translationPending = $translationPending;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslationPending()
	{
		return $this->translationPending;
	}


	/**
	 * @param text $translationPending2
	 * @return Translation
	 */
	public function setTranslationPending2($translationPending2)
	{
		$this->translationPending2 = $translationPending2;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getTranslationPending2()
	{
		return $this->translationPending2;
	}


	/**
	 * @param datetime $translated
	 * @return Translation
	 */
	public function setTranslated($translated)
	{
		$this->translated = $translated;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getTranslated()
	{
		return $this->translated;
	}


	/**
	 * @param json $variations
	 * @return Translation
	 */
	public function setVariations($variations)
	{
		$this->variations = $variations;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getVariations()
	{
		return $this->variations;
	}

}
