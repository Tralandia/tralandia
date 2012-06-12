<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\UploadControl,
	Extras\Types\Address;


class AdvancedDateTimePicker extends \DateTimePicker {

	public static function register() {
		Container::extensionMethod('addAdvancedDateTimePicker', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedDateTimePicker($label);
		});
	}

}