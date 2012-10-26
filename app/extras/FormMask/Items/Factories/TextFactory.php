<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class TextFactory {
	
	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @return Extras\FormMask\Items\Text
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\Text($name, $label, $entity);
	}
}