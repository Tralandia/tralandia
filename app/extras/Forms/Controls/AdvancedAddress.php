<?php

namespace Extras\Forms\Controls;


use Entity,
	Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl,
	Nette\Forms\Controls\SelectBox;

class AdvancedAddress extends BaseControl {

	const ROW1 = 'row1';
	const ROW2 = 'row2';
	const CITY = 'city';
	const COUNTRY = 'country';
	const POSTCODE = 'postcode';

	private $items = array();

	public function setAddress(Entity\Contacts\Address $address = null) {
		$address ? $this->setDefaultValue(array(
			self::ROW1 => $address->row1,
			self::ROW2 => $address->row2,
			self::CITY => $address->city,
			self::POSTCODE => $address->postcode,
			self::COUNTRY => $address->country->id,
		)) : array();
		return $this;
	}

	public function setCountryItems(array $items) {
		$this->items = $items;
		return $this;
	}

	public function getControl() {
		$wrapper = Html::el('div')->class('address-wrapper row-fluid');
		$control = parent::getControl();
		$name = $control->name;
		$value = $this->getValue();
		$id = $control->id;

		$input = Html::el('input');
		$fields = array(
			self::ROW1 => array('class' => 'span4', 'placeholder' => 'Address Line 1'),
			self::ROW2 => array('class' => 'span4', 'placeholder' => 'Address Line 2'),
			self::POSTCODE => array('class' => 'span2', 'placeholder' => 'Postcode'),
			self::CITY => array('class' => 'span6'),
		);

		foreach ($fields as $field => $params) {
			$input->id = $id . '-' . $field;
			$input->type = 'text';
			$input->name = $name . "[$field]";
			$input->value = $value[$field];
			$input->class = $params['class'];
			$input->placeholder = isset($params['placeholder']) ? $params['placeholder'] : null;
			$wrapper->add((string) $input);
		}
		$select = Html::el('select')->name($name . '[' . self::COUNTRY . ']');

		foreach ($this->items as $key => $val) {
			$option = Html::el('option')->value($key)->setText($val);
			if ($key == $value[self::COUNTRY]) {
				$option->selected('selected');
			}
			$select->add($option);
		}

		$wrapper->add((string) $select);

		return $wrapper;
	}

	/**
	 * Registracia kontrolu
	 */
	public static function register() {
		Container::extensionMethod('addAdvancedAddress', function(Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedAddress($label);
		});
	}
}