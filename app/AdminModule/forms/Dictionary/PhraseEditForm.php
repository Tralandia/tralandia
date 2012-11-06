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
		$translation = $this->phraseService->getTranslation($language);

		foreach ($translation->variations as $pluralKey => $genders) {
			$plural = $container->addContainer($pluralKey);
			foreach ($genders as $genderKey => $genders) {
				$gender = $plural->addContainer($genderKey);
				foreach ($genders as $saceKey => $saceValue) {
					if($phrase->type->isSimple()) {
						$field = $gender->addText($saceKey, $saceValue);
					} else {
						$field = $gender->addTextArea($saceKey, $saceValue);
					}
					if($setDisabled) $field->setDisabled();
				}
			}
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