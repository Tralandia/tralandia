<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\TextArea;


class AdvancedNeon extends TextArea {

	/**
	 * Adds addCheckboxList() method to Nette\Forms\Container
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedNeon', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedNeon($label);
		});
	}

}