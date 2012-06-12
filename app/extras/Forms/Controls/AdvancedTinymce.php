<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\TextBase,
	Extras\Types\Address;


class AdvancedTinymce extends TextBase {

	public function __construct($label = NULL, $cols = NULL, $rows = NULL) {
		parent::__construct($label);
		$this->control->addClass('tinymce');
	}


	public static function register() {
		Container::extensionMethod('addAdvancedTinymce', function (Container $_this, $name, $label, $cols = NULL, $rows = NULL) {
			return $_this[$name] = new AdvancedTinymce($label, $cols, $rows);
		});
	}

}