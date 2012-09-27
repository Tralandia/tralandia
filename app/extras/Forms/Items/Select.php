<?php

namespace Extras\Forms\Items;

use Nette;

/**
 * Select polozka masky
 */
class Select extends Base {

	/** @var array */
	protected $items;

	/**
	 * Setter gettera poloziek
	 * @param array
	 * @return Select
	 */
	public function setItemsGetter($items) {
		$this->items = $items;
		return $this;
	}

	/**
	 * Getter gettera poloziek
	 * @return Select
	 */
	public function getItemsGetter() {
		return $this->items;
	}

	/**
	 * Vrati vsetky polozky
	 * @return array
	 */
	public function getItems() {
		return call_user_func($this->getItemsGetter());
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addSelect($this->getName(), $this->getLabel(), $this->getItems())
			->setDefaultValue($this->getValue());
	}
}