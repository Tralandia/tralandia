<?php

namespace Extras\Forms\Container;

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

	protected function build($disabled)
	{
		$translation = $this->translation;
		$phrase = $translation->getPhrase();

		foreach ($translation->getVariations() as $pluralKey => $genders) {
			$plural = $this->addContainer($pluralKey);
			foreach ($genders as $genderKey => $genderValue) {
				$gender = $plural->addContainer($genderKey);
				foreach ($genderValue as $caseKey => $caseValue) {
					if($phrase->getType()->isHtml()) {
						$field = $gender->addText($caseKey, $caseValue);
					} else {
						$field = $gender->addTextArea($caseKey, $caseValue);
						$field->getControlPrototype()->class('texyla');
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

}

