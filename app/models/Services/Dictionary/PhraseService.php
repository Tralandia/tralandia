<?php

namespace Tra\Services\Dictionary;

use Tra,
	Tra\Services,
	Nette\Utils\Strings;

class PhraseService extends \Tra\Services\BaseService {

	const MAIN_ENTITY_NAME = '\Dictionary\Phrase';

	// public function addLanguage(LanguageService $languageService) {
	// 	$languages = $this->getMainEntity()->languages;
	// 	if(!$languages->contains($languageService->getMainEntity())) {
	// 		$this->getMainEntity()->addLanguage($languageService->getMainEntity());
	// 	}
	// 	return $this;
	// }
	
	// public function removeLanguage(LanguageService $languageService) {
	// 	$languages = $this->getMainEntity()->languages;
	// 	if($languages->contains($languageService->getMainEntity())) {
	// 		$this->getMainEntity()->removeLanguage($languageService->getMainEntity());
	// 	}
	// 	return $this;
	// }

	public function createTranslation(array $data) {
		$translation = new TranslationService();
		$translation->language = $data['language']->getMainEntity();
		$translation->translation = $data['translation'];
		$translation->translation2 = $data['translation'];
		$translation->translationWebalized = Strings::webalize($data['translation']);
		$translation->translationWebalized2 = Strings::webalize($data['translation']);
		$translation->translationPending = '';
		$translation->translationPending2 = '';
		$translation->translated = new \Nette\DateTime;
		$translation->save();
		
		return $this->addTranslation($translation);
	}

	protected function addTranslation(TranslationService $translation) {
		$this->getMainEntity()->addTranslation($translation->getMainEntity());
		return $this;
	}

	public function getTranslation(LanguageService $languageService) {
		return $this->em->createQueryBuilder()
			->select('t')
			->from('\Dictionary\Translation', 't')
			->where('t.phrase = :phrase')
			->andWhere('t.language = :language')
			->setParameters(array(
				'phrase' => $this->getMainEntity(),
				'language' => $languageService->getMainEntity(),
			))->getQuery()
			->getSingleResult();
	}

	public function getTranslatedTranslations() {}
	public function getPendingTranslations() {}
	public function activateTranslations(LanguageService $languageService) {}
}