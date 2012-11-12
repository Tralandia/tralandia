<?php

namespace Extras\FormMask\Items\Foctories;

use Extras\Models\Entity\IEntity;

/**
 * @author Branislav Vaculčiak
 */
interface IFactory {

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 */
	public function create($name, $label, IEntity $entity);
}
