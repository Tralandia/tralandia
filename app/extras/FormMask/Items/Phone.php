<?php

namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Phone polozka masky
 */
class Phone extends Email {

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @param Extras\Books\Phone
	 */
	public function __construct($name, $label, \Entity\BaseEntity $entity, Extras\Books\Phone $book) {
		$this->name = $name;
		$this->label = $label;
		$this->entity = $entity;
		$this->book = $book;

		$this->setValueGetter(new Extras\Callback($this, 'getChildrenValue'));
		$this->setValueSetter(new Extras\Callback($this, 'setChildrenValue'));
	}
}
