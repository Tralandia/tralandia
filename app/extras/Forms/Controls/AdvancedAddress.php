<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl,
	Extras\Types\Address;


class AdvancedAddress extends BaseControl {

	public function setValue($value) {
		if(!is_array($value)) $value = (array) $value;
		$this->value = $value;
		return $this;
	}

	public function getValue()
	{
		return is_array($this->value) ? $this->value : NULL;
	}


	public function getControl() {
		$wrapper = Html::el('div')->class('address-wrapper');
		$control = parent::getControl();
		$name = $control->name;
		$id = $control->id;

		foreach (array(Address::ADDRESS, Address::ADDRESS2, Address::ZIPCODE, Address::COUNTRY) as $value) {
			$control->id = $id . '-'.$value;
			$control->name = $name . "[$value]";
			$control->value = $this->value[$value];
			$wrapper->add((string) $control);
		}

		// $values = $this->getValue();
		return $wrapper;
	}

	/**
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedAddress', function (Container $_this, $name, $label, array $structure = NULL) {
			return $_this[$name] = new AdvancedAddress($label, $structure);
		});
	}

}