<?php

namespace Service\Phrase;

use Service, Doctrine, Entity;

/**
 * Sluzba frazy
 * @author Branislav Vaculčiak
 */
class PhraseService extends Service\BaseService {

	protected $phraseTranslationRepositoryAccessor;

	public function injectRepositories(\Nette\DI\Container $dic)
	{
		$this->phraseTranslationRepositoryAccessor = $dic->phraseTranslationRepositoryAccessor;
	}

	public function createTranslation(Entity\Language $language, $translationText = NULL) {
		$type = $this->getEntity()->type;
		if(!$type instanceof \Entity\Phrase\Type) {
			throw new \Nette\InvalidArgumentException('Set phrase type before creating translations.');
		}
		// @todo toto dorobit 
		$translation = $this->phraseTranslationRepositoryAccessor->get()->createNew();
		$this->addTranslation($translation);
		$translation->timeTranslated = new \Nette\DateTime();
		$translation->language = $language;
		$translation->variations = $this->getTranslationVariationsMatrix($language);
		if($translationText !== NULL) $translation->translation = $translationText;
		
		return $translation;
	}

	/**
	 * Vrati spravny preklad na zaklade jazyka
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getTranslation(Entity\Language $language) {
		return $this->entity->getTranslations()->filter(function($entity) use ($language) {
			return $entity->language->id == $language->id;
		})->current();
	}

	public function hasTranslation($language) {
		return $this->getTranslation($language) instanceof Entity\Phrase\Translation;
	}

	/**
	 * Vrati hodnotu prekladu v textovej forme na zaklade jazyka
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getTranslateValue(Entity\Language $language) {
		return (string) $this->getTranslation($language);
	}

	/**
	 * Ulozi hodnotu prekladu na zaklade jazyka
	 * @param Entity\Language
	 * @param string
	 * @return Phrase
	 */
	public function setTranslateValue(Entity\Language $language, $value) {
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
			$plurals = $language->getDefaultPluralsNames();
		}

		if($this->getEntity()->type->genderVariationsRequired) {
			$genders = $language->getGendersNames();
		} else {
			$genders = $language->getDefaultGendersNames();
		}

		if($this->getEntity()->type->locativesRequired) {
			$cases = $language->getCasesNames();
		} else {
			$cases = $language->getDefaultCasesNames();
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