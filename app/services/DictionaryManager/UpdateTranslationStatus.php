<?php

namespace Dictionary;

use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;

class UpdateTranslationStatus {


	/**
	 * @param Phrase $phrase
	 *
	 * @return Phrase
	 */
	public function updatePhrase(Phrase $phrase)
	{
		$sourceTranslation = $phrase->getSourceTranslation();
		$centralTranslation = $phrase->getCentralTranslation();
		$sourceAndCentralAreSame = $sourceTranslation == $centralTranslation;

		if(!$sourceAndCentralAreSame && $sourceTranslation->getTimeTranslated() > $centralTranslation->getTimeTranslated()) {
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

}
