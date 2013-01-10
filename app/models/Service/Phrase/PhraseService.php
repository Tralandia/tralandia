<?php

namespace Service\Phrase;

use Service, Doctrine, Entity;
use Nette\Utils\Strings;

/**
 * Sluzba frazy
 * @author Branislav Vaculčiak
 */
class PhraseService extends Service\BaseService {

	const REQUESTED = 1;
	const CENTRAL = 5;
	const SOURCE = 10;

	public function createTranslation(Entity\Language $language, $translationText = NULL) {
		return $this->getEntity()->createTranslation($language, $translationText);
	}

	/**
	 * Vrati translation-y v ziadanom, centralom a source jazyku, ak existuju
	 *
	 * @param \Entity\Language $language
	 * @return array[Entity\Phrase\Translation]
	 */
	public function getMainTranslations(Entity\Language $language = NULL) {
		return $this->getEntity()->getMainTranslations($language);
	}

	/**
	 * Vrati spravny preklad na zaklade jazyka
	 * @param \Entity\Language $language
	 * @param bool $loose
	 *
	 * @return mixed|null
	 */
	public function getTranslation(Entity\Language $language, $loose = FALSE) {
		return $this->getEntity()->getTranslation($language, $loose);
	}

	/**
	 * Vrati source Language
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getSourceTranslation() {
		return $this->getEntity()->getSourceTranslation();
	}

	public function hasTranslation($language) {
		return $this->getEntity()->hasTranslation($language);
	}

	/**
	 * Vrati hodnotu prekladu v textovej forme na zaklade jazyka
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getTranslationText(Entity\Language $language, $loose = FALSE) {
		return $this->getEntity()->getTranslationText($language, $loose);
	}

	/**
	 * Returns the number of translations that contain any text (length > 0)
	 * @return int
	 */
	public function getValidTranslationsCount() {
		return $this->getEntity()->getValidTranslationsCount();
	}

	/**
	 * Checks for existing central language translation
	 * @return boolean
	 */
	public function hasCentralTranslation() {
		return $this->getEntity()->hasCentralTranslation();
	}

	/**
	 * Checks for existing source language translation
	 * @return boolean
	 */
	public function hasSourceTranslation() {
		return $this->getEntity()->hasSourceTranslation();
	}

	/**
	 * Ulozi hodnotu prekladu na zaklade jazyka
	 * @param Entity\Language
	 * @param string
	 * @return Phrase
	 */
	public function setTranslationText(Entity\Language $language, $value) {
		return $this->getEntity()->setTranslationText();
	}

}