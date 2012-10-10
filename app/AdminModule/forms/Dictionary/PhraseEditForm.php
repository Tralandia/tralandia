<?php

namespace AdminModule\Forms\Dictionary;

class PhraseEditForm extends \AdminModule\Forms\Form {

	public $phraseTypeRepository;
	public $languageRepository;
	public $language;

	public function __construct($phraseTypeRepository, $languageRepository, $language) {
		$this->phraseTypeRepository = $phraseTypeRepository;
		$this->languageRepository = $languageRepository;
		$this->language = $language;
		parent::__construct();
	}

	protected function buildForm() {
		$typeList = $this->phraseTypeRepository->fetchPairs('id', 'name');
		$this->addSelect('phraseType', 'Type:', $typeList);

		$languageList = $this->languageRepository->fetchPairs('id', 'iso');
		$this->addSelect('sourceLanguage', 'Source Language:', $languageList);

		$this->addCheckbox('ready', 'Ready');
		$this->addCheckbox('corrected', 'Corrected');

		$genderList = $this->language->genders;
		d($genderList);
		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);
		$this->addSelect('gender', 'Gender', $genderList);
		$this->addSelect('position', 'Position', $positionList);
	}

}
