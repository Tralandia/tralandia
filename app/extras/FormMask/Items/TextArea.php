<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * Textarea polozka masky
 */
class Textarea extends Text {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addAdvancedTextarea($this->getName(), $this->getLabel())
			->setDefaultValue($this->getValue())
			->setDisabled($this->disabled);
	}
}