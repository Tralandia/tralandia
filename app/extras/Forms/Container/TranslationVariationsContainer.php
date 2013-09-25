<?php

namespace Extras\Forms\Container;

use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;

class TranslationVariationContainer extends BaseContainer
{

	/**
	 * @var \Entity\Phrase\Translation
	 */
	protected $translation;

	/**
	 * @param \Entity\Phrase\Translation $translation
	 * @param bool $disabled
	 */
	public function __construct(\Entity\Phrase\Translation $translation, $disabled = FALSE)
	{
		$this->translation = $translation;
		parent::__construct();
		$this->build($disabled);
	}


	/**
	 * @param $disabled
	 */
	protected function build($disabled)
	{
		$translation = $this->translation;
		$phrase = $translation->getPhrase();
		$translationText = $translation->getTranslation();
		$isHtml = $phrase->getType()->isHtml();

		foreach ($translation->getVariations() as $pluralKey => $genders) {
			$plural = $this->addContainer($pluralKey);
			foreach ($genders as $genderKey => $genderValue) {
				$gender = $plural->addContainer($genderKey);
				foreach ($genderValue as $caseKey => $caseValue) {
					if($isHtml || strlen($translationText) >= 60 ) {
						$field = $gender->addTextArea($caseKey, $caseValue);
					} else {
						$field = $gender->addText($caseKey, $caseValue);
					}
					if($disabled) $field->setDisabled();
				}
			}
		}
	}


	/**
	 * @return \Entity\Language|NULL
	 */
	public function getLanguage()
	{
		return $this->translation->getLanguage();
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getPhrase()
	{
		return $this->translation->getPhrase();
	}

	public function getMainControl()
	{
		throw new \Nette\NotImplementedException;
	}


	/**
	 * @return string
	 */
	public function getStatusLabel()
	{
		$translation = $this->translation;
		$phrase = $this->getPhrase();

		$translationStatus = $translation->getStatus();
		$phraseStatus = $phrase->getStatus();

		$languageName = $translation->getLanguage()->getName()->getCentralTranslationText();

		if($phraseStatus == Phrase::WAITING_FOR_CENTRAL) {
			return 'Waiting for English to be Prepared for Correction';
		} else if ($phraseStatus == Phrase::WAITING_FOR_CORRECTION) {
			return 'Waiting for English to be Corrected by translator';
		} else if ($phraseStatus == Phrase::WAITING_FOR_CORRECTION_CHECKING) {
			return 'Waiting for English to be Accepted by Admin';
		} else if ($phraseStatus == Phrase::READY && $translationStatus == Translation::WAITING_FOR_TRANSLATION) {
			return 'Waiting for ' . $languageName .' to be Translated';
		} else if ($translationStatus == Translation::WAITING_FOR_CHECKING) {
			return 'Waiting for ' . $languageName .' to be Translated';
		} else if ($translationStatus == Translation::WAITING_FOR_PAYMENT) {
			return 'Waiting for translation to be paid for';
		} else if ($phraseStatus == Phrase::READY && $translationStatus == Translation::UP_TO_DATE) {
			return 'Complete and up to date';
		}

	}

}

