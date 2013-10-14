<?php

namespace Extras\FormMask\Items\Foctories;

use \Entity\BaseEntity;

/**
 * @author Branislav Vaculčiak
 */
interface IFactory {

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 */
	public function create($name, $label, BaseEntity $entity);
}
