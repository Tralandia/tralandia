<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * Select polozka masky
 */
class Select extends Base {

	/** @var array */
	protected $itemsGetter;

	/** @var array */
	protected $itemsParams;

	/**
	 * Setter gettera poloziek
	 * @param array
	 * @return Select
	 */
	public function setItemsGetter(array $items, array $params = null) {
		$this->itemsGetter = $items;
		$this->itemsParams = $params;
		return $this;
	}

	/**
	 * Getter gettera poloziek
	 * @return Select
	 */
	public function getItemsGetter() {
		return $this->itemsGetter;
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