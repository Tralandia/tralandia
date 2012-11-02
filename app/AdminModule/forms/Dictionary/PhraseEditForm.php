<?php

namespace AdminModule\Forms\Dictionary;

class PhraseEditForm extends \AdminModule\Forms\Form {

	public $phraseService, $fromLanguage, $toLanguage;
	public $languageRepositoryAccessor;
	public $sourceLanguage;

	public function __construct($phraseService, \Entity\Language $fromLanguage, \Entity\Language $toLanguage, $languageRepositoryAccessor, $sourceLanguage) {
		list($this->phraseService,
			$this->fromLanguage,
			$this->toLanguage,
			$this->languageRepositoryAccessor,
			$this->sourceLanguage) = func_get_args();
		parent::__construct();
	}

	protected function buildForm() {
		$languageList = $this->languageRepositoryAccessor->get()->fetchPairs('id', 'iso');
		$this->addSelect('sourceLanguage', 'Source Language:', $languageList);

		$this->addCheckbox('ready', 'Ready');
		$this->addCheckbox('corrected', 'Corrected');

		$fromTranslations = $this->addContainer('fromTranslations');
		$this->fillTranslationsContainer($fromTranslations, $this->fromLanguage, TRUE);

		$this->addSelect('changeToLanguage', 'changeToLanguage', $languageList);

		$toTranslations = $this->addContainer('toTranslations');
		$this->fillTranslationsContainer($toTranslations, $this->toLanguage);

		$genderList = $this->toLanguage->genders;
		$this->addSelect('gender', 'Gender', $genderList);

		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);
		$this->addSelect('position', 'Position', $positionList);

		$this->addSubmit('save', 'Save');
	}

	public function fillTranslationsContainer($container, $language, $setDisabled = NULL) {
		$phrase = $this->phraseService->getEntity();

		if($phrase->type->pluralVariationsRequired) {
			$plurals = $language->plurals;
		} else {
			$plurals = array('default' => 'default');
		}

		if($phrase->type->genderVariationsRequired) {
			$genders = $language->genders;
		} else {
			$genders = array('default');
		}

		if($phrase->type->locativesRequired) {
			$pady = array('nominative' => 'Nominative', 'locative' => 'Locative');
		} else {
			$pady = array('default');
		}

		$i = 1;
		foreach ($plurals as $pluralKey => $pluralValue) {
			$plural = $container->addContainer($pluralKey);
			foreach ($genders as $genderKey => $genderValue) {
				$gender = $plural->addContainer($genderKey);
				foreach ($pady as $padKey => $padValue) {
					if($phrase->type->isSimple()) {
						$field = $gender->addText($padKey, $padValue);
					} else {
						$field = $gender->addTextArea($padKey, $padValue);
					}
					if($setDisabled) $field->setDisabled();
				}
			}
			$i++;
		}
	}

	public function setDefaultValues() {
		$phrase = $this->phraseService->getEntity();
		$toTranslation = $this->phraseService->getTranslation($this->toLanguage);
		$this->setDefaults(array(
			'phraseType' => $phrase->type->id,
			'sourceLanguage' => $phrase->sourceLanguage->id,
			'ready' => $phrase->ready,
			'corrected' => $phrase->corrected,
			'fromTranslations' => $this->phraseService->getTranslation($this->fromLanguage)->variations,
			'toTranslations' => $toTranslation->variations,
			'gender' => $toTranslation->gender,
			'position' => $toTranslation->position,
		));
	}

}