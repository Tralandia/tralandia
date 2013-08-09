<?php

namespace Dictionary;

use Entity\Phrase\Phrase;
use Entity\Phrase\Translation;
use Entity\User\User;
use Nette\NotImplementedException;

class UpdateTranslationStatus {


	/**
	 * @param Phrase $phrase
	 *
	 * @throws \Nette\NotImplementedException
	 * @return Phrase
	 */
	public function resolvePhrase(Phrase $phrase)
	{
		throw new NotImplementedException('Toto sa uz nepouziva');


		$sourceTranslation = $phrase->getSourceTranslation();
		$centralTranslation = $phrase->getCentralTranslation();

		$sourceAndCentralAreSame = $sourceTranslation == $centralTranslation;
		$sourceIsNewerThenCentral = $sourceTranslation->getTimeTranslated() > $centralTranslation->getTimeTranslated();

		$phrase->setStatus(Phrase::READY);

		if(!strlen($centralTranslation->getTranslation())) {
			foreach($phrase->getTranslations() as $translation) {
				if($translation == $sourceTranslation) {
					$translation->setStatus(Translation::UP_TO_DATE);
					continue;
				}
				$translation->setStatus(Translation::WAITING_FOR_CENTRAL);
			}
			$centralTranslation->setStatus(Translation::WAITING_FOR_TRANSLATION);

			return $phrase;
		}

		if(!$centralTranslation->isComplete()) {
			foreach($phrase->getTranslations() as $translation) {
				if($translation == $sourceTranslation) {
					$translation->setStatus(Translation::UP_TO_DATE);
					continue;
				}
				$translation->setStatus(Translation::WAITING_FOR_CENTRAL);
			}
			$centralTranslation->setStatus(Translation::WAITING_FOR_TRANSLATION);

			$phrase->setStatus(Phrase::WAITING_FOR_CENTRAL);

			return $phrase;
		}

		if($sourceAndCentralAreSame) {
			foreach($phrase->getTranslations() as $translation) {
				if($translation == $sourceTranslation) {
					$translation->setStatus(Translation::UP_TO_DATE);
					continue;
				}
				if($centralTranslation->getTimeTranslated() > $translation->getTimeTranslated() || strlen($translation->getTranslation()) == 0 ) {
					$translation->setStatus(Translation::WAITING_FOR_TRANSLATION);
				} else {
					$translation->setStatus(Translation::UP_TO_DATE);
				}
			}
			return $phrase;
		} else {
			if($sourceIsNewerThenCentral) {
				return $this->sourceIsNewest($phrase);
			} else {
			}
		}
	}


	/**
	 * @param Phrase $phrase
	 *
	 * @return Phrase
	 */
	public function setAsUpToDate(Phrase $phrase)
	{
		$phrase->setStatus(Phrase::READY);

		foreach($phrase->getTranslations() as $translation) {
			$translation->setStatus(Translation::UP_TO_DATE);
		}

		return $phrase;

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

		$phrase->setStatus(Phrase::WAITING_FOR_CENTRAL);

		return $phrase;
	}


	public function resolveTranslation(Translation $translation)
	{
		$phrase = $translation->getPhrase();
		$centralTranslation = $phrase->getCentralTranslation();
		$centralTranslationStatus = $centralTranslation->getStatus();

		if($centralTranslationStatus == Translation::UP_TO_DATE) {
			//$translation->setStatus()
		}

	}


	/**
	 * @param Translation $translation
	 * @param User $user
	 *
	 * @throws TranslationsNotCompleteException
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function translationUpdated(Translation $translation, User $user)
	{
		$isUserTranslator = $translation->getLanguage()->getTranslator() == $user;
		$isUserAdmin = $user->isSuperAdmin();

		if(!$translation->isComplete() && $isUserTranslator) {
			throw new TranslationsNotCompleteException;
		}

		$phrase = $translation->getPhrase();

		$sourceTranslation = $phrase->getSourceTranslation();
		$centralTranslation = $phrase->getCentralTranslation();
		$sourceAndCentralAreSame = $sourceTranslation == $centralTranslation;



		if($translation == $sourceTranslation && $sourceAndCentralAreSame) {
			if($isUserTranslator) {
				$this->update_one($translation);
			} else if($isUserAdmin) {
				$this->update_two($translation);
			} else {
				$this->update_two($translation);
			}
		} else if($translation == $sourceTranslation && !$sourceAndCentralAreSame) {
			if($isUserTranslator) {
				$this->update_three($translation);
			} else if($isUserAdmin) {
				$this->update_three($translation);
			} else {
				$this->update_three($translation);
			}
		} else if($translation == $centralTranslation && !$sourceAndCentralAreSame) {
			if($isUserTranslator) {
				$this->update_one($translation);
			} else if($isUserAdmin) {
				$this->update_two($translation);
			} else {
				$this->update_two($translation);
			}
		} else {
			if($isUserTranslator) {
				$translation->setStatus(Translation::WAITING_FOR_CHECKING);
			} else if($isUserAdmin) {
				// nic...
			} else {
				$translation->setStatus(Translation::UP_TO_DATE);
			}
		}

		return $phrase;
	}


	protected function update_one(Translation $translation)
	{
		$phrase = $translation->getPhrase();
		$phrase->setStatus(Phrase::WAITING_FOR_CORRECTION_CHECKING);

		foreach($phrase->getTranslations() as $value) {
			if($translation == $value) {
				$value->setStatus(Translation::WAITING_FOR_CHECKING);
			} else {
				$value->setStatus(Translation::WAITING_FOR_CENTRAL);
			}
		}
	}

	protected function update_two(Translation $translation)
	{
		$phrase = $translation->getPhrase();
		$phrase->setStatus(Phrase::WAITING_FOR_CORRECTION);

		foreach($phrase->getTranslations() as $value) {
			if($translation == $value) {
				$value->setStatus(Translation::WAITING_FOR_TRANSLATION);
			} else {
				$value->setStatus(Translation::WAITING_FOR_CENTRAL);
			}
		}
	}

	protected function update_three(Translation $translation)
	{
		$phrase = $translation->getPhrase();
		$centralTranslation = $phrase->getCentralTranslation();

		$phrase->setStatus(Phrase::WAITING_FOR_CENTRAL);

		foreach($phrase->getTranslations() as $value) {
			if($translation == $value) {
				$value->setStatus(Translation::UP_TO_DATE);
			} else if($value == $centralTranslation) {
				$value->setStatus(Translation::WAITING_FOR_TRANSLATION);
			} else {
				$value->setStatus(Translation::WAITING_FOR_CENTRAL);
			}
		}
	}

	public function setPhraseReady(Phrase $phrase, User $user) {
		$this->_specialUpdate(
			$phrase,
			$user,
			Phrase::READY,
			Translation::WAITING_FOR_PAYMENT,
			Translation::WAITING_FOR_TRANSLATION
		);
	}

	public function setPhraseReadyForCorrection(Phrase $phrase, User $user) {
		$this->_specialUpdate(
			$phrase,
			$user,
			Phrase::WAITING_FOR_CORRECTION,
			Translation::WAITING_FOR_TRANSLATION,
			Translation::WAITING_FOR_CENTRAL
		);
	}


	protected function _specialUpdate(Phrase $phrase, User $user, $phraseStatus, $centralStatus, $otherTranslations)
	{
		$phrase->setStatus($phraseStatus);
		$centralTranslation = $phrase->getCentralTranslation();
		$sourceTranslation = $phrase->getSourceTranslation();
		$sourceAndCentralAreSame = $sourceTranslation == $centralTranslation;

		foreach($phrase->getTranslations() as $value) {
			if (!$sourceAndCentralAreSame && $value == $sourceTranslation) {
				$value->setStatus(Translation::UP_TO_DATE);
			} else if($value == $centralTranslation) {
				$value->setStatus($centralStatus);
			} else {
				$value->setStatus($otherTranslations);
			}
		}
	}

}


class TranslationsNotCompleteException extends \Exception {}
