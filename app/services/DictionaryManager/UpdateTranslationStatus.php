<?php

namespace Dictionary;

use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Entity\User\Role;
use Entity\User\User;

class UpdateTranslationStatus {


	/**
	 * @param Phrase $phrase
	 *
	 * @return Phrase
	 */
	public function resolvePhrase(Phrase $phrase)
	{
		$sourceTranslation = $phrase->getSourceTranslation();
		$centralTranslation = $phrase->getCentralTranslation();

		$sourceAndCentralAreSame = $sourceTranslation == $centralTranslation;
		$sourceAreNewestThenCentral = $sourceTranslation->getTimeTranslated() > $centralTranslation->getTimeTranslated();

		if(!$sourceAndCentralAreSame && $sourceAreNewestThenCentral) {
			return $this->sourceIsNewest($phrase);
		} else {
			foreach($phrase->getTranslations() as $translation) {
				if($translation == $sourceTranslation || $translation == $centralTranslation) {
					$translation->setStatus(Translation::UP_TO_DATE);
				}
				if($centralTranslation->getTimeTranslated() > $translation->getTimeTranslated()) {
					$translation->setStatus(Translation::WAITING_FOR_TRANSLATION);
				}
			}
			return $phrase;
		}
	}


	/**
	 * @param Phrase $phrase
	 *
	 * @return Phrase
	 */
	protected function sourceIsNewest(Phrase $phrase)
	{
		$sourceTranslation = $phrase->getSourceTranslation();
		$centralTranslation = $phrase->getCentralTranslation();

		$sourceTranslation->setStatus(Translation::UP_TO_DATE);
		$centralTranslation->setStatus(Translation::WAITING_FOR_TRANSLATION);

		foreach($phrase->getTranslations() as $translation) {
			if($translation == $sourceTranslation || $translation == $centralTranslation) continue;
			$translation->setStatus(Translation::WAITING_FOR_CENTRAL);
		}

		return $phrase;
	}


	/**
	 * @param Translation $translation
	 * @param User $user
	 *
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function translationUpdated(Translation $translation, User $user)
	{
		$phrase = $translation->getPhrase();

		$sourceTranslation = $phrase->getSourceTranslation();
		$centralTranslation = $phrase->getCentralTranslation();
		$sourceAndCentralAreSame = $sourceTranslation == $centralTranslation;

		if(!$sourceAndCentralAreSame && $translation == $centralTranslation) {
			$this->sourceIsNewest($phrase);
		} else if($translation == $sourceTranslation) {
			if($user == $translation->getLanguage()->getTranslator()) {
				$phrase->setStatus(Phrase::WAITING_FOR_CORRECTION_CHECKING);
				$sourceTranslation->setStatus(Translation::WAITING_FOR_CHECKING);
			} else if($user->isSuperAdmin()) {
				$phrase->setStatus(Phrase::WAITING_FOR_CORRECTION);
				$sourceTranslation->setStatus(Translation::WAITING_FOR_TRANSLATION);
			} else {
				$phrase->setStatus(Phrase::WAITING_FOR_CENTRAL);
				$sourceTranslation->setStatus(Translation::UP_TO_DATE);
			}

			foreach($phrase->getTranslations() as $value) {
				//if($sourceAndCentralAreSame && $value == $sourceTranslation) continue;
				if($value == $centralTranslation || $value == $sourceTranslation) continue;
				$value->setStatus(Translation::WAITING_FOR_CENTRAL);
			}
		} else {
			$translation->setStatus(Translation::WAITING_FOR_CHECKING);
		}

		return $phrase;
	}

}
