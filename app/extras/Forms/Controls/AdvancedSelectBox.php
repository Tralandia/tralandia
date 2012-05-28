<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\SelectBox,
	Nette\Utils\Html;


class AdvancedSelectBox extends SelectBox {

	/**
	 * Sets items from which to choose.
	 * @param  array
	 * @return SelectBox  provides a fluent interface
	 */
	public function setItems(array $items, $useKeys = TRUE)
	{
		parent::setItems($items, $useKeys);
		$this->allowed = array();

		foreach ($items as $key => $value) {
			if (!is_array($value)) {
				$value = array($key => $value);
			}

			foreach ($value as $key2 => $value2) {
				if($value2 instanceof Html) {
					$this->allowed[$key2] = $value2->getText();
				} else {
					$this->allowed[$key2] = $value2;
				}
			}
		}

		return $this;
	}


	public function getControl() {
		$control = parent::getControl();
		
		$wrapper = Html::el('div')->addClass('select-wrapper');
		$wrapper->add($control);
		$controlId = $control->getId();

		$buttonsWrapper = Html::el('div')->addClass('select-buttons btn-group');

		if($this->getOption('inlineEditing')) {
			$editingHtml = $this->getOption('inlineEditing');
			$editingHtml->addAttributes(array(
				'for-control' => $controlId,
			));
			$buttonsWrapper->add($editingHtml);

		}

		if($this->getOption('inlineDeleting')) {
			$buttonsWrapper->add($this->getOption('inlineDeleting'));
		}
		if($this->getOption('inlineCreating')) {
			$buttonsWrapper->add($this->getOption('inlineCreating'));
		}
		return $wrapper->add($buttonsWrapper);
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
