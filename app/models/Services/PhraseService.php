<?php

namespace Tra\Services;

use Tra;

class PhraseService extends BaseService {

	const MAIN_ENTITY_NAME = '\Dictionary\Phrase';

	public function addLanguage(\Language $language) {
		
	}
	
	public function removeLanguage(\Language $language) {}
	public function getTranslation(\Language $language) {}
	public function getTranslatedTranslations() {}
	public function getPendingTranslations() {}
	public function activateTranslations(\Language $language) {}
}