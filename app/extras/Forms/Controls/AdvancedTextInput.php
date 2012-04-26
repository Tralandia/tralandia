<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\TextInput,
	Nette\Utils\Html;


class AdvancedTextInput extends TextInput implements IAdvancedControl {

	protected $inlineEditing;
	protected $inlineCreating;
	protected $inlineDeleting;

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

	public function setInlineDeleting($inlineDeleting) {
		$this->inlineDeleting = $inlineDeleting;
		return $this;		
	}

	public function getInlineDeleting() {
		return $this->inlineDeleting;
	}


	public function getControl() {
		$control = parent::getControl();
		$control->addClass('pull-left');
		$wrapper = Html::el('div')->addClass('input-append input-prepend');
		$wrapper->add($control);
		if($this->getInlineEditing()) {
			$wrapper->add(Html::el('a')->add(Html::el('i')->addClass('icon-edit'))->addClass('btn pull-left'));
		}
		if($this->getInlineDeleting()) {
			$wrapper->add(Html::el('a')->add(Html::el('i')->addClass('icon-remove'))->addClass('btn pull-left'));
		}
		if($this->getInlineCreating()) {
			$wrapper->add(Html::el('a')->add(Html::el('i')->addClass('icon-plus'))->addClass('btn pull-left'));
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
