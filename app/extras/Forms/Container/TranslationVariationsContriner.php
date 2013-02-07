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
	 * @param bool $setDisabled
	 */
	public function __construct(\Entity\Phrase\Translation $translation, $setDisabled = FALSE)
	{
		$this->translation = $translation;
		parent::__construct();
		$this->build($setDisabled);
	}

	protected function build($setDisabled)
	{
		$translation = $this->translation;
		$phrase = $translation->getPhrase();

		foreach ($translation->getVariations() as $pluralKey => $genders) {
			$plural = $this->addContainer($pluralKey);
			foreach ($genders as $genderKey => $genderValue) {
				$gender = $plural->addContainer($genderKey);
				foreach ($genderValue as $caseKey => $caseValue) {
					if($phrase->getType()->isSimple()) {
						$field = $gender->addText($caseKey, $caseValue);
					} else {
						$field = $gender->addTextArea($caseKey, $caseValue);
					}
					if($setDisabled) $field->setDisabled();
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

