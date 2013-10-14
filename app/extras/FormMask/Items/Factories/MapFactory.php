<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class MapFactory implements IFactory {

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @return Extras\FormMask\Items\Map
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Map($name, $label, $entity);
	}
}
