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

	public function duplicate($save = FALSE) {
		$newPhrase = self::get();

		if(isset($this->ready)) $newPhrase->ready = $this->ready;
		if(isset($this->sourceLanguage)) $newPhrase->sourceLanguage = $this->sourceLanguage;
		if(isset($this->details)) $newPhrase->details = $this->details;
		//$newPhrase-> = NULL;

		foreach ($this->translations as $translation) {
			$newTranslation = TranslationService::get();
			$newTranslation->language = $translation->language;
			$newTranslation->variations = $translation->variations;
			if(isset($translation->timeTranslated)) $newTranslation->timeTranslated = $translation->timeTranslated;
			if(isset($translation->checked)) $newTranslation->checked = $translation->checked;
			$newPhrase->addTranslation($newTranslation);
		}
		if($save) {
			$newPhrase->save();
		}
		return $newPhrase;
	}
	
}
