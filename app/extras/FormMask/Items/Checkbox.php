<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * Checkbox polozka masky
 */
class Checkbox extends Text {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addAdvancedCheckbox($this->getName(), $this->getLabel())
			->setDefaultValue($this->getValue());
	}
}