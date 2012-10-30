<?php

namespace AdminModule\Forms\Dictionary;

class PhraseEditForm extends \AdminModule\Forms\Form {

	public $phraseTypeRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $sourceLanguage;

	public function __construct($phraseTypeRepositoryAccessor, $languageRepositoryAccessor, $sourceLanguage) {
		$this->phraseTypeRepositoryAccessor = $phraseTypeRepositoryAccessor;
		$this->languageRepositoryAccessor = $languageRepositoryAccessor;
		$this->sourceLanguage = $sourceLanguage;
		parent::__construct();
	}

	protected function buildForm() {

		$typeList = $this->phraseTypeRepositoryAccessor->get()->fetchPairs('id', 'name');
		$this->addSelect('phraseType', 'Type:', $typeList);

		$languageList = $this->languageRepositoryAccessor->get()->fetchPairs('id', 'iso');
		$this->addSelect('sourceLanguage', 'Source Language:', $languageList);

		$this->addCheckbox('ready', 'Ready');
		$this->addCheckbox('corrected', 'Corrected');

		$genderList = $this->sourceLanguage->genders;
		$positionList = array(
			'before' => 'Before',
			'after' => 'After',
		);
		$this->addSelect('gender', 'Gender', $genderList);
		$this->addSelect('position', 'Position', $positionList);
	}

}
