<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\UploadControl,
	Extras\Types\Address;


class AdvancedDatePicker extends \DatePicker {

	public static function register() {
		Container::extensionMethod('addAdvancedDatePicker', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedDatePicker($label);
		});
	}
}