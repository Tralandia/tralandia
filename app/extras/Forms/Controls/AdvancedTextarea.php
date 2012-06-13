<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\TextArea,
	Extras\Types\Address;


class AdvancedTextarea extends TextArea {

	public static function register() {
		Container::extensionMethod('addAdvancedTextarea', function (Container $_this, $name, $label, $cols = NULL, $rows = NULL) {
			return $_this[$name] = new AdvancedTextarea($label, $cols, $rows);
		});
	}

}