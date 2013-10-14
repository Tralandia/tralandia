<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class YesNoFactory implements IFactory {

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @return Extras\FormMask\Items\YesNo
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\YesNo($name, $label, $entity);
	}
}
