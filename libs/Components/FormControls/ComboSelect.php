<?php

require_once LIBS_DIR . '/Nette/Forms/Controls/SelectBox.php';

class ComboSelect extends Nette\Forms\Controls\SelectBox {

	public function __construct($form, $name, $label, array $items = NULL, $size = NULL) {
		parent::__construct($label, $items, $size);
		$form->addHidden('_' . $name);
	}

	public function isNewValue() {
		$hidden = $this->getForm()->getComponent('_' . $this->getName(), false);
		if ($hidden && $hidden->isFilled() && $this->getSelectedItem() !== $hidden->getValue()) {
			return true;
		}
		return false;
	}

	public function getNewValue() {
		if ($this->isNewValue()) {
			$hidden = $this->getForm()->getComponent('_' . $this->getName());
			return $hidden->getValue();
		}
		return null;
	}

	public function isFilled() {
		if ($this->isNewValue()) {
			return true;
		}
		return (string) $this->getValue() !== '';
	}
}
