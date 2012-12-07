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
	 * @param Extras\Models\Entity\IEntity
	 * @return Extras\FormMask\Items\Phrase
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\ReadOnlyPhrase($name, $label, $entity, $this->environment);
	}
}