<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\TextInput,
	Nette\Utils\Html;


class AdvancedTextInput extends TextInput {

	public $originalValue;

	public function setValue($value) {
		$this->originalValue = $value;
		return parent::setValue($value);
	}

	public function getControl() {

		$value = $this->getValue();

		$inlineEditing = NULL;
		if($this->getOption('inlineEditing')) {
			$inlineEditing = $this->getOption('inlineEditing');
			$inlineEditing->href($inlineEditing->href->setParameter('id', $this->originalValue->getPhraseId()));
		}
		
		$inlineDeleting = NULL;
		if($this->getOption('inlineDeleting')) {
			$inlineDeleting = $this->getOption('inlineDeleting');
			$inlineDeleting->href($inlineDeleting->href->setParameter('id', $this->originalValue->getPhraseId()));
		}
		
		$inlineCreating = NULL;
		if($this->getOption('inlineCreating')) {
			$inlineCreating = $this->getOption('inlineCreating');
		}

		$control = parent::getControl();

		$control->addClass('pull-left');
		$wrapper = Html::el('div')->addClass('input-append input-prepend');
		$wrapper->add($control);

		$inlineEditing ? $wrapper->add($inlineEditing) : NULL;
		$inlineDeleting ? $wrapper->add($inlineDeleting) : NULL;
		$inlineCreating ? $wrapper->add($inlineCreating) : NULL;

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