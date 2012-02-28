<?php

namespace Tra\Services;

use Tra;

class PhraseService extends BaseService {

	const MAIN_ENTITY_NAME = '\Dictionary\Phrase';

	public function addLanguage(LanguageService $languageService) {
		$languages = $this->getMainEntity()->languages;
		if(!$languages->contains($languageService->getMainEntity())) {
			$this->getMainEntity()->addLanguage($languageService->getMainEntity());
		}
		return $this;
	}
	
	public function removeLanguage(LanguageService $languageService) {
		$languages = $this->getMainEntity()->languages;
		if($languages->contains($languageService->getMainEntity())) {
			$this->getMainEntity()->removeLanguage($languageService->getMainEntity());
		}
		return $this;
	}
	public function getTranslation(\Language $language) {}
	public function getTranslatedTranslations() {}
	public function getPendingTranslations() {}
	public function activateTranslations(\Language $language) {}
}