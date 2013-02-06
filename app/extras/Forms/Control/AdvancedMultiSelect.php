<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\SelectBox,
	Nette\Utils\Html;


class AdvancedMultiSelect extends SelectBox {

	/**
	 * Adds addCheckboxList() method to Nette\Forms\Container
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedMultiSelect', function (Container $_this, $name, $label = NULL, array $items = NULL, $size = NULL) {
			return $_this[$name] = new AdvancedMultiSelect($label, $items, $size);
		});
	}

}
