<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\SelectBox,
	Nette\Utils\Html;


class AdvancedSelectBox extends SelectBox implements IAdvancedControl {

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
		$controlId = $control->getId();
		if($this->getInlineEditing()) {
			$editingHtml = Html::el('a')->add(Html::el('i')->addClass('icon-edit'))->addClass('btn pull-left edit');
			$editingHtml->addAttributes(array(
				'for-control' => $controlId,
			));
			$wrapper->add($editingHtml);

		}
		if($this->getInlineDeleting()) {
			$wrapper->add(Html::el('a')->add(Html::el('i')->addClass('icon-remove'))->addClass('btn pull-left delete'));
		}
		if($this->getInlineCreating()) {
			$wrapper->add(Html::el('a')->add(Html::el('i')->addClass('icon-plus'))->addClass('btn pull-left create'));
		}
		return $wrapper;
	}

	/**
	 * Adds addCheckboxList() method to Nette\Forms\Container
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedSelectBox', function (Container $_this, $name, $label = NULL, array $items = NULL, $size = NULL) {
			return $_this[$name] = new AdvancedSelectBox($label, $items, $size);
		});
	}

}
