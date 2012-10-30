<?php

namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Select polozka masky
 */
class Select extends Base {

	/** @var Extras\Callback */
	protected $itemsGetter;

	/**
	 * Setter gettera poloziek
	 * @param Extras\Callback
	 * @return Select
	 */
	public function setItemsGetter(Extras\Callback $items) {
		$this->itemsGetter = $items;
		return $this;
	}

	/**
	 * Getter gettera poloziek
	 * @return Extras\Callback
	 */
	public function getItemsGetter() {
		return $this->itemsGetter;
	}

	/**
	 * Vrati vsetky polozky
	 * @return mixed
	 */
	public function getItems() {
		if (!is_callable($this->getItemsGetter())) {
			throw new InvalidStateException("Nebol zadaný callback gettera poloziek.");
		}
		return $this->getItemsGetter()->invoke();
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addAdvancedSelect($this->getName(), $this->getLabel(), $this->getItems())
			->setDefaultValue($this->getValue());
	}
}