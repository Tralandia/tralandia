<?php

namespace Service\Phrase;

use Service, Doctrine, Entity;

/**
 * Sluzba frazy
 * @author Branislav Vaculčiak
 */
class PhraseService extends Service\BaseService {

	/**
	 * Vrati spravny preklad na zaklade jazyka
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getTranslation(Entity\Language $language) {
		return $this->entity->getTranslations()->filter(function($entity) use ($language) {
			return $entity->language == $language;
		})->current();
	}

	/**
	 * Vrati hodnotu prekladu v textovej forme na zaklade jazyka
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getTranslateValue(Entity\Language $language) {
		return $this->entity->getTranslations()->filter(function($entity) use ($language) {
			return $entity->language == $language;
		})->current()->translation;
	}

	/**
	 * Ulozi hodnotu prekladu na zaklade jazyka
	 * @param Entity\Dictionary\Language
	 * @param string
	 * @return Phrase
	 */
	public function setTranslateValue(Entity\Dictionary\Language $language, $value) {
		$this->entity->getTranslations()->filter(function($entity) use ($language) {
			return $entity->language == $language;
		})->current()->translation = $value;
		$this->save(); // TODO: ma byt save tunaka?
		return $this;
	}

	public function addTranslation($translation) {
		return $this->getEntity()->addTranslation($translation);
	}
}