<?php

namespace Service\Dictionary;


class Phrase extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Dictionary\Phrase';

	public function addTranslation($translation) {
		if($translation instanceof Translation) {
			$translation = $translation->getMainEntity();
		} else if($translation instanceof \Entity\Dictionary\Translation) {

		} else {
			throw new \Nette\InvalidArgumentException('$translation argument does not match with the expected value');
		}

		if(!$this->getTranslation($translation->language)) {
			$this->getMainEntity()->addTranslation($translation);
		} else {
			throw new \Extras\Models\Exceptions\TranslationAlreadyExistException('Translation for "'.$translation->language->iso.'" already exist', 1);
		}
	}

	public function getTranslation($language, $returnTranslationAsString = FALSE) {
		if($language instanceof Language) {
			$language = $language->getMainEntity();
		} else if($language instanceof \Entity\Dictionary\Language) {

		} else {
			throw new \Nette\InvalidArgumentException('$language argument does not match with the expected value');
		}

		$translations = $this->translations;

		$data = NULL;
		foreach ($translations as $key => $val) {
			if($val->language->id == $language->id) {
				$data = $val;
				break;
			}
		}

		return $data ? ($returnTranslationAsString ? Translation::get($data)->translation : Translation::get($data)) : NULL;
	}

	public function hasTranslation($language) {
		if($language instanceof Language) {
			$language = $language->getMainEntity();
		} else if($language instanceof \Entity\Dictionary\Language) {

		} else {
			throw new \Nette\InvalidArgumentException('$language argument does not match with the expected value');
		}

		return (bool) $this->getTranslation($language);
	}

	public function duplicate($save = FALSE) {
		$newPhrase = self::get();

		if(isset($this->ready)) $newPhrase->ready = $this->ready;
		if(isset($this->sourceLanguage)) $newPhrase->sourceLanguage = $this->sourceLanguage;
		if(isset($this->details)) $newPhrase->details = $this->details;
		//$newPhrase-> = NULL;

		foreach ($this->translations as $translation) {
			$newTranslation = Translation::get();
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
