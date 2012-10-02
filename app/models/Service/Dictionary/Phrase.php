<?php

namespace Service\Dictionary;

use Service, Doctrine, Entity;

/**
 * Sluzba frazy
 * @author Branislav Vaculčiak
 */
class Phrase extends Service\Base {

	/**
	 * Ukazkovy proces
	 */
	public function getTranslate(Entity\Dictionary\Language $language) {
		return $this->entity->getTranslations()->filter(function($entity) use ($language) {
			return $entity->language == $language;
		})->current();
	}
}