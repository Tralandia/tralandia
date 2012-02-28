<?php

namespace Tra\Services;

use Tra;

class PhraseService extends BaseService {

	const MAIN_ENTITY_NAME = '\Dictionary\Phrase';

	public function addLanguage(LanguageService $language) {
		$this->getMainEntity()->addLanguage($language->getMainEntity());
	}
	
	public function removeLanguage(LanguageService $language) {
		$this->getMainEntity()->removeLanguage($language->getMainEntity());
	}
	public function getTranslation(\Language $language) {}
	public function getTranslatedTranslations() {}
	public function getPendingTranslations() {}
	public function activateTranslations(\Language $language) {}
}