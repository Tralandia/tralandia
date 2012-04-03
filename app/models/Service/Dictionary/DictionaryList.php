<?php

namespace Service\Dictionary;


class DictionaryList extends \Extras\Models\ServiceList {
	
	const SOURCE_LANGUAGE = 1;


	public function getSourceLanguage() {
		return LanguageService::get(self::SOURCE_LANGUAGE);
	}
	
}
