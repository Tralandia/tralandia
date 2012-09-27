<?php

namespace Extras\Forms\Items;

use Nette;

/**
 * Checkbox polozka masky
 */
class Checkbox extends Base {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addCheckbox($this->getName(), $this->getLabel())
			->setDefaultValue($this->getValue());
	}
}