<?php

namespace Extras\FormMask\Items\Foctories;

use Extras, Entity;

/**
 * @author Branislav Vaculčiak
 */
class ReadOnlyPhraseFactory extends AdvancedPhraseFactory implements IFactory {

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @return Extras\FormMask\Items\Phrase
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\ReadOnlyPhrase($name, $label, $entity, $this->environment);
	}
}
