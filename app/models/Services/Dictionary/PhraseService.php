<?php

namespace Services\Dictionary;


class PhraseService extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Phrase';


	public function getTranslation($language) {
		if($language instanceof LanguageService) {
			$language = $language->getMainEntity();
		} else if($language instanceof \Entities\Dictionary\Language) {

		} else {
			throw new \Nette\InvalidArgumentException('$language argument does not match with the expected value');
		}

		$translations = $this->translations;

		$data = NULL;
		foreach ($translations as $key => $val) {
			if($val->language == $language) {
				$data = $val;
				break;
			}
		}

		return $data ? TranslationService::get($data) : NULL;
	}
	
}
