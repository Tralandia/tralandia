<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\SelectBox,
	Extras\Types\Address;


class AdvancedAddress extends SelectBox {

	public function setItems(array $items, $useKeys = TRUE) {
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

	public function getSelectedEntry()
	{
		$selected = $this->getValue();
		return array($selected[Address::COUNTRY] => true);
	}


	public function getControl() {
		$wrapper = Html::el('div')->class('address-wrapper row-fluid');
		$control = parent::getControl();
		$name = $control->name;
		$id = $control->id;

		$input = Html::el('input');
		$fields = array(
			Address::ADDRESS => array('class' => 'span8', 'placeholder' => 'Address Line 1'),
			Address::ADDRESS2 => array('class' => 'span3', 'placeholder' => 'Address Line 2'),
			Address::LOCALITY => array('class' => 'span8', 'placeholder' => 'Locality'),
			Address::POSTCODE => array('class' => 'span3', 'placeholder' => 'Postcode'),
			Address::COUNTRY => array('class' => 'span11'),
		);

		foreach ($fields as $field => $params) {
			if($field == Address::COUNTRY) {
				$control->id = $id . '-'.$field;
				$control->name = $name . "[$field]";
				$control->class = $params['class'];
				$wrapper->add((string) $control);
				continue;
			}
			$input->id = $id . '-'.$field;
			$input->name = $name . "[$field]";
			$input->value = $this->value[$field];
			$input->class = $params['class'];
			$input->placeholder = $params['placeholder'];
			$wrapper->add((string) $input);
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