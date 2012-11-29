<?php

namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Email polozka masky
 */
class Email extends Base {

	/** @var Extras\Models\Entity\IEntity */
	protected $entity;

	/** @var Extras\Books\Email */
	protected $book;

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @param Extras\Books\Email
	 */
	public function __construct($name, $label, Extras\Models\Entity\IEntity $entity, Extras\Books\Email $book) {
		$this->name = $name;
		$this->label = $label;
		$this->entity = $entity;
		$this->book = $book;

		$this->setValueGetter(new Extras\Callback($this, 'getChildrenValue'));
		$this->setValueSetter(new Extras\Callback($this, 'setChildrenValue'));
	}

	public function getChildrenValue() {
		$children = $this->entity->{$this->getterMethodName($this->name)};
		if ($children) {
			return $children()->getValue();
		}
		return null;
	}

	public function setChildrenValue($value) {
		$this->entity->{$this->setterMethodName($this->name)}($this->book->getOrCreate($value));
	}
}