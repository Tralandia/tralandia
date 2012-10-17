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

	public function hasTranslation($language) {
		return $this->getTranslation($language) instanceof \Entity\Phrase\Translation;
	}

	/**
	 * Vrati spravny preklad na zaklade jazyka
	 * @param Entity\Language
	 * @return Entity\Phrase\Translation
	 */
	public function getTranslate22(Entity\Language $language) {
		return $this->entity->getTranslations()->filter(function($entity) use ($language) {
			return $entity->language == $language;
		})->current();
	}

	public function addTranslation($translation) {
		return $this->getEntity()->addTranslation($translation);
	}
}