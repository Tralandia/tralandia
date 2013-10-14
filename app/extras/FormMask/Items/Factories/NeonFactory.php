<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class NeonFactory implements IFactory {

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @return Extras\FormMask\Items\Neon
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Neon($name, $label, $entity);
	}
}
