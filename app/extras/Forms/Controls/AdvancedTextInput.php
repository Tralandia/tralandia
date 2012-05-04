<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\TextInput,
	Nette\Utils\Html;


class AdvancedTextInput extends TextInput {

	public function getControl() {
		$control = parent::getControl();
		$control->addClass('pull-left');
		$wrapper = Html::el('div')->addClass('input-append input-prepend');
		$wrapper->add($control);
		if($this->getOption('inlineEditing')) {
			$wrapper->add($this->getOption('inlineEditing'));
		}
		if($this->getOption('inlineDeleting')) {
			$wrapper->add($this->getOption('inlineDeleting'));
		}
		if($this->getOption('inlineCreating')) {
			$wrapper->add($this->getOption('inlineCreating'));
		}
		return $wrapper;
	}

	/**
	 * Adds addCheckboxList() method to Nette\Forms\Container
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedTextInput', function (Container $_this, $name, $label = NULL, $cols = NULL, $maxLength = NULL) {
			return $_this[$name] = new AdvancedTextInput($label, $cols, $maxLength);
		});
	}

}
