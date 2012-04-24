<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\TextInput,
	Nette\Utils\Html;


class AdvancedTextInput extends TextInput implements IAdvancedControl {

	protected $inlineEditing;
	protected $inlineCreating;

	public function setInlineEditing($inlineEditing) {
		$this->inlineEditing = $inlineEditing;
		return $this;		
	}

	public function getInlineEditing() {
		return $this->inlineEditing;
	}

	public function setInlineCreating($inlineCreating) {
		$this->inlineCreating = $inlineCreating;
		return $this;		
	}

	public function getInlineCreating() {
		return $this->inlineCreating;
	}


	public function getControl() {
		$control = parent::getControl();
		$wrapper = Html::el('span')->addClass('input-wrapper');
		$wrapper->add($control);
		if($this->getInlineEditing()) {
			$wrapper->add(Html::el('a')->add('Editable'));
		}
		if($this->getInlineCreating()) {
			$wrapper->add(Html::el('a')->add('Creatable'));
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
