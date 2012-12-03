<?php

namespace Service\Phrase;

use Service, Doctrine, Entity;

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
		$type = $this->getEntity()->type;
		if(!$type instanceof \Entity\Phrase\Type) {
			throw new \Nette\InvalidArgumentException('Set phrase type before creating translations.');
		}
		$translation = new Entity\Phrase\Translation;

		$this->addTranslation($translation);
		$translation->timeTranslated = new \Nette\DateTime();
		$translation->language = $language;
		$translation->variations = $this->getTranslationVariationsMatrix($language);
		if($translationText !== NULL) $translation->translation = $translationText;
		
		return $translation;
	}

	/**
	 * Vrati translation-y v ziadanom, centralom a source jazyku, ak existuju
	 * @param Entity\Language
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
	 * Ulozi hodnotu prekladu na zaklade jazyka
	 * @param Entity\Language
	 * @param string
	 * @return Phrase
	 */
	public function setTranslationText(Entity\Language $language, $value) {
		$this->getTranslation($language)->translation = $value;
		return $this;
	}

	public function addTranslation($translation) {
		return $this->getEntity()->addTranslation($translation);
	}

	public function getTranslationVariationsMatrix($language) {
		if($this->getEntity()->type->pluralVariationsRequired) {
			$plurals = $language->getPluralsNames();
		} else {
			$plurals = $language->getDefaultPluralName();
		}
 
		if($this->getEntity()->type->genderVariationsRequired) {
			$genders = $language->getGendersNames();
		} else {
			$genders = $language->getDefaultGenderName();
		}
 
		if($this->getEntity()->type->locativesRequired) {
			$cases = $language->getCasesNames();
		} else {
			$cases = $language->getDefaultCaseName();
		}

		$matrix = array();
		foreach ($plurals as $pluralKey => $pluralValue) {
			foreach ($genders as $genderKey => $genderValue) {
				foreach ($cases as $caseKey => $caseValue) {
					$matrix[$pluralKey][$genderKey][$caseKey] = NULL;
				}
			}
		}

		return $matrix;
	}
}