<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\SelectBox,
	Nette\Utils\Html;


class AdvancedSelectBox extends SelectBox {

	public function getControl() {
		$control = parent::getControl();
		
		$control->addClass('pull-left');
		$wrapper = Html::el('div')->addClass('input-append input-prepend');
		$wrapper->add($control);
		$controlId = $control->getId();
		if($this->getOption('inlineEditing')) {
			$editingHtml = $this->getOption('inlineEditing');
			$editingHtml->addAttributes(array(
				'for-control' => $controlId,
			));
			$wrapper->add($editingHtml);

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
		Container::extensionMethod('addAdvancedSelectBox', function (Container $_this, $name, $label = NULL, array $items = NULL, $size = NULL) {
			return $_this[$name] = new AdvancedSelectBox($label, $items, $size);
		});
	}

}
