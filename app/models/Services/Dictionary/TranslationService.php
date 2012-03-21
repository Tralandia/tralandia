<?php

namespace Services\Dictionary;


class TranslationService extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Translation';

	public function save() {
		$this->setWebalizedTexts();
		parent::save();
	}
	

	public function setWebalizedTexts() {
		$type = $this->phrase->type();
		//if($type instanceof)
	}
}
