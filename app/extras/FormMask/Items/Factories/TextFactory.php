<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class TextFactory implements IFactory {

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @return Extras\FormMask\Items\Text
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Text($name, $label, $entity);
	}
}
