<?php

namespace Service\Dictionary;

use Service, Doctrine, Entity;

/**
 * Sluzba frazy
 * @author Branislav Vaculčiak
 */
class Phrase extends Service\Base {

	/**
	 * Vrati spravny preklad na zaklade jazyka
	 * @param Entity\Dictionary\Language
	 * @return Entity\Dictionary\Translation
	 */
	public function getTranslate(Entity\Dictionary\Language $language) {
		return $this->entity->getTranslations()->filter(function($entity) use ($language) {
			return $entity->language == $language;
		})->current();
	}

	/**
	 * Vrati hodnotu prekladu na zaklade jazyka
	 * @param Entity\Dictionary\Language
	 * @return string
	 */
	public function getTranslateValue(Entity\Dictionary\Language $language) {
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
}