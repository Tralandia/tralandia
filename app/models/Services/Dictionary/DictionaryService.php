<?php

namespace Services\Dictionary;


class DictionaryService extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Phrase';

	const SOURCE_LANGUAGE = 1;

	public function toTranslate() {

	}

	public function getSourceLanguage() {
		return new LanguageService(self::SOURCE_LANGUAGE);
	}
	
}
