<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Tra\Utils\Strings,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl,
	Extras\Types\Address;


class AdvancedSuggestion extends BaseControl {

	// public function setValue($value) {
	// 	if(!is_array($value)) $value = $value->toArray();
	// 	$this->value = $value;
	// 	return $this;
	// }

	// public function getValue()
	// {
	// 	return is_array($this->value) ? $this->value : NULL;
	// }


	public function getControl() {
		$fakeInput = Html::el('input')->class('fake');
		$control = parent::getControl();
		return $fakeInput.$control;
	}

	/**
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedSuggestion', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedSuggestion($label);
		});
	}

}