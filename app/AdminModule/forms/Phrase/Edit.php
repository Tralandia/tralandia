<?php

namespace AdminModule\Forms\Phrase;

class Edit extends \AdminModule\Forms\Form {

	public function __construct($parent, $name) {
		parent::__construct($parent, $name);

		$languages = \Service\Dictionary\LanguageList::getBySupportedAsPairs('id', 'iso', true);
		debug($languages);
		$this->addSelect('source_languages', 'Source Language', $languages);
		
		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function onSuccess(Edit $form) {
		
	}

}
