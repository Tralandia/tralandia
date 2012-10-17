<?php

namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Text polozka masky
 */
class Text extends Base {

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 */
	public function __construct($name, $label, Extras\Models\Entity\IEntity $entity) {
		$this->name = $name;
		$this->label = $label;
		$this->setValueGetter(new Extras\Callback($entity, $this->getterMethodName($this->name), array($entity)));
		$this->setValueSetter(new Extras\Callback($entity, $this->setterMethodName($this->name), array($entity)));
	}
}