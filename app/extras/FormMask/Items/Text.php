<?php

namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Text polozka masky
 */
class Text extends Base {

	/** @var \Entity\BaseEntity */
	protected $entity;

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 */
	public function __construct($name, $label, \Entity\BaseEntity $entity) {
		$this->name = $name;
		$this->label = $label;
		$this->entity = $entity;
		$this->setValueGetter(new Extras\Callback($entity, $this->getterMethodName($this->name)));
		$this->setValueSetter(new Extras\Callback($entity, $this->setterMethodName($this->name)));
	}
}
