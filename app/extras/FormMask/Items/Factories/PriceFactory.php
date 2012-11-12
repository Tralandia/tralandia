<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class PriceFactory implements IFactory {
	
	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @return Extras\FormMask\Items\Price
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\Price($name, $label, $entity);
	}
}