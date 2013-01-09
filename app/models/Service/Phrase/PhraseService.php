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

	public $centralLanguage;

	public function createTranslation(Entity\Language $language, $translationText = NULL) {
		return $this->getEntity()->createTranslation($language, $translationText);
	}

	/**
	 * Vrati translation-y v ziadanom, centralom a source jazyku, ak existuju
	 *
	 * @param \Entity\Language $language
	 * @return Entity\Phrase\Translation
	 */
	public function getMainTranslations(Entity\Language $language) {
		$t = array();

		foreach ($this->entity->getTranslations() as $key => $value) {
			if ($value->language->id == $language->id) {
				$t[self::REQUESTED] = $value;
			}

			if ($value->language->id == $this->centralLanguage) {
				$t[self::CENTRAL] = $value;
			}

			if ($this->entity->sourceLanguage && $value->language->id == $this->entity->sourceLanguage->id) {
				$t[self::SOURCE] = $value;
			}
		}

		ksort($t);

		return $t;
	}

	/**
	 * Vrati spravny preklad na zaklade jazyka
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getTranslation(Entity\Language $language, $loose = FALSE) {
		$t = $this->getMainTranslations($language);
		if ($loose) {
			$t = array_filter($t);
			return reset($t);
		} else {
			return (array_key_exists(self::REQUESTED, $t) ? $t[self::REQUESTED] : NULL);
		}
	}

	/**
	 * Vrati source Language
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getSourceTranslation() {
		foreach ($this->entity->getTranslations() as $key => $value) {
			if ($this->entity->sourceLanguage && $value->language->id == $this->entity->sourceLanguage->id) {
				return $value;
			}
		}
		return FALSE;
	}

	public function hasTranslation($language) {
		return $this->getTranslation($language) instanceof Entity\Phrase\Translation;
	}

	/**
	 * Vrati hodnotu prekladu v textovej forme na zaklade jazyka
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getTranslationText(Entity\Language $language, $loose = FALSE) {
		$t = $this->getMainTranslations($language);
		$text = '';
		if ($loose) {
			foreach ($t as $key => $value) {
				if (strlen((string) $value)) {
					$text = (string) $value;
					break;
				}
			}
		} else {
			$text = $t[self::REQUESTED];
		}

		return (string) $text;
	}

	/**
	 * Returns the number of translations that contain any text (length > 0)
	 * @return int
	 */
	public function getValidTranslationsCount() {
		$c = 0;
		foreach ($this->entity->getTranslations() as $key => $value) {
			if (Strings::length($value->translation) > 0) $c++;
		}

		return $c;
	}

	/**
	 * Checks for existing central language translation
	 * @return boolean
	 */
	public function hasCentralTranslation() {
		$mainTranslations = $this->getMainTranslations();
		if (Strings::length($mainTranslations[self::CENTRAL]->translation) > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Checks for existing source language translation
	 * @return boolean
	 */
	public function hasSourceTranslation() {
		$mainTranslations = $this->getMainTranslations();
		if (Strings::length($mainTranslations[self::SOURCE]->translation) > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Ulozi hodnotu prekladu na zaklade jazyka
	 * @param Entity\Language
	 * @param string
	 * @return Phrase
	 */
	public function setTranslationText(Entity\Language $language, $value) {
		$this->getTranslation($language)->translation = $value;
		return $this;
	}

	private function addTranslation($translation) {
		return $this->getEntity()->addTranslation($translation);
	}
}