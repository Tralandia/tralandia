<?php

namespace Service\Dictionary;

use Service, Doctrine, Entity;

/**
 * Sluzba frazy
 * @author Branislav Vaculčiak
 */
class PhraseService extends Service\BaseService {

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
	 * Vrati spravny preklad na zaklade jazyka
	 * @param Entity\Dictionary\Language
	 * @return Entity\Dictionary\Translation
	 */
	public function getTranslate22(Entity\Dictionary\Language $language) {
		return $this->entity->getTranslations()->filter(function($entity) use ($language) {
			return $entity->language == $language;
		})->current();
	}

	public function addTranslation($translation) {
		return $this->getEntity()->addTranslation($translation);
	}
}