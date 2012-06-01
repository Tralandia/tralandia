<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Controls\SelectBox,
	Nette\Forms\Container;


class AdvancedPrice extends SelectBox {

	/**
	 * Sets items from which to choose.
	 * @param  array
	 * @return SelectBox  provides a fluent interface
	 */
	public function setItems(array $items, $useKeys = TRUE)
	{
		parent::setItems($items, $useKeys);
		$this->allowed = array();

		foreach ($items as $key => $value) {
			if (!is_array($value)) {
				$value = array($key => $value);
			}

			foreach ($value as $key2 => $value2) {
				if($value2 instanceof Html) {
					$this->allowed[$key2] = $value2->getText();
				} else {
					$this->allowed[$key2] = $value2;
				}
			}
		}

		return $this;
	}


	public function setValue($value) {
		if(!is_array($value)) $value = $value->toArray();
		$this->value = $value;
		return $this;
	}

	public function getValue()
	{
		return is_array($this->value) ? $this->value : NULL;
	}


	public function getControl() {
		$control = parent::getControl();
		
		$name = $control->name;
		$id = $control->id;

		$control->class = NULL;
		$control->name .= '[currency]';
		$control->id .= '-currency';
		$control->value = $this->value['currency'];

		$wrapper = Html::el('div')->addClass('select-wrapper');

		$priceInput = Html::el('input')->name($name.'[amount]')->id($id.'-amount');
		// debug($this->value);
		$priceInput->value = $this->value['amount'];

		$wrapper->add($priceInput.$control);

		return $wrapper;
	}

	/**
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedPrice', function (Container $_this, $name, $label = NULL, array $items = NULL, $size = NULL) {
			return $_this[$name] = new AdvancedPrice($label, $items, $size);
		});
	}

}