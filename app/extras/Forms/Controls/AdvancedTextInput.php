<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\TextInput,
	Nette\Utils\Html;


class AdvancedTextInput extends TextInput {

	public $defaultParam;

	public function setDefaultParam($value) {
		$this->defaultParam = $value;
	}

	public function getControl() {
		$value = $this->getValue();

		$inlineEditing = NULL;
		if($this->getOption('inlineEditing')) {
			$inlineEditing = $this->getOption('inlineEditing');
			$inlineEditing->href($inlineEditing->href->setParameter('id', $this->defaultParam));
		}
		
		$inlineDeleting = NULL;
		if($this->getOption('inlineDeleting')) {
			$inlineDeleting = $this->getOption('inlineDeleting');
			$inlineDeleting->href($inlineDeleting->href->setParameter('id', $this->defaultParam));
		}
		
		$inlineCreating = NULL;
		if($this->getOption('inlineCreating')) {
			$inlineCreating = $this->getOption('inlineCreating');
		}

		$control = parent::getControl();

		$control->addClass('input-large');
		$wrapper = Html::el('div')->addClass('input-wrapper');
		$wrapper->add($control);

		$buttonsWrapper = Html::el('div')->addClass('input-buttons btn-group');

		$inlineEditing ? $buttonsWrapper->add($inlineEditing) : NULL;
		$inlineDeleting ? $buttonsWrapper->add($inlineDeleting) : NULL;
		$inlineCreating ? $buttonsWrapper->add($inlineCreating) : NULL;

		return $wrapper->add($buttonsWrapper);
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