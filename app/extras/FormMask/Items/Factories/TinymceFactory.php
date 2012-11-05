<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class TinymceFactory {
	
	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @return Extras\FormMask\Items\Tinymce
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\Tinymce($name, $label, $entity);
	}
}