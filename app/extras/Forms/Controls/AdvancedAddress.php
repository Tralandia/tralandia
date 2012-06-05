<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl,
	Extras\Types\Address;


class AdvancedAddress extends BaseControl {

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
		$wrapper = Html::el('div')->class('address-wrapper row-fluid');
		$control = parent::getControl();
		$name = $control->name;
		$id = $control->id;

		$fields = array(
			Address::ADDRESS => array(
				'class' => 'span8'),
			Address::ADDRESS2 => array(
				'class' => 'span3'),
			Address::LOCALITY => array(
				'class' => 'span8'),
			Address::POSTCODE => array(
				'class' => 'span3'),
			Address::COUNTRY => array(
				'class' => 'span11'),
		);

		foreach ($fields as $field => $params) {
			debug($field);
			$control->id = $id . '-'.$field;
			$control->name = $name . "[$field]";
			$control->value = $this->value[$field];
			$control->class = $params['class'];
			$control->placeholder = $field;
			$wrapper->add((string) $control);
		}

		// $values = $this->getValue();
		return $wrapper;
	}

	/**
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedAddress', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedAddress($label);
		});
	}

}