<?php

namespace AdminModule\Forms\Dictionary;

class PhraseEditForm extends \AdminModule\Forms\Form {

	public $fromLanguage, $toLanguage, $phrase;
	public $phraseTypeRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $sourceLanguage;

	public function __construct(\Entity\Language $fromLanguage,\Entity\Language $toLanguage, $phrase, $phraseTypeRepositoryAccessor, $languageRepositoryAccessor, $sourceLanguage) {
		list($this->fromLanguage, 
			$this->toLanguage,
			$this->phrase,
			$this->phraseTypeRepositoryAccessor, 
			$this->languageRepositoryAccessor, 
			$this->sourceLanguage) = func_get_args();
		parent::__construct();
	}

	protected function buildForm() {

		$typeList = $this->phraseTypeRepositoryAccessor->get()->fetchPairs('id', 'name');
		$this->addSelect('phraseType', 'Type:', $typeList)->setDisabled();

		$languageList = $this->languageRepositoryAccessor->get()->fetchPairs('id', 'iso');
		$this->addSelect('sourceLanguage', 'Source Language:', $languageList)->setDisabled();

		$this->addCheckbox('ready', 'Ready');
		$this->addCheckbox('corrected', 'Corrected');

		$fromTranslations = $this->addContainer('fromTranslations');
		$this->fillTranslationsContainer($fromTranslations, $this->fromLanguage, TRUE);


		$toTranslations = $this->addContainer('toTranslations');
		$this->fillTranslationsContainer($toTranslations, $this->toLanguage);

		$genderList = $this->toLanguage->genders;
		$this->addSelect('gender', 'Gender', $genderList);

		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);
		$this->addSelect('position', 'Position', $positionList);
	}

	public function fillTranslationsContainer($container, $language, $setDisabled = NULL) {
		if($this->phrase->type->pluralVariationsRequired) {
			$plurals = $language->plurals;
		} else {
			$plurals = array('default' => 'default');
		}

		if($this->phrase->type->genderVariationsRequired) {
			$genders = $language->genders;
		} else {
			$genders = array('default');
		}

		if($this->phrase->type->locativesRequired) {
			$pady = array('nominative', 'locative');
		} else {
			$pady = array('default');
		}

		$i = 1;
		foreach ($plurals as $pluralKey => $pluralValue) {
			$plural = $container->addContainer($pluralKey);
			foreach ($genders as $genderKey => $genderValue) {
				$gender = $plural->addContainer($genderValue);
				foreach ($pady as $padKey => $padValue) {
					if($this->phrase->type->isSimple()) {
						$field = $gender->addText($padValue, $padValue);
					} else {
						$field = $gender->addTextArea($padValue, $padValue);
					}
					if($setDisabled) $field->setDisabled();
				}
			}
			$i++;
		}
	}

	public function setDefaultValues() {
		$this->setDefaults(array(
			'phraseType' => $this->phrase->type->id,
			'sourceLanguage' => $this->phrase->sourceLanguage->id,
		));
	}

}