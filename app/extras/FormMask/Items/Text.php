<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * Text polozka masky
 */
class Text extends Base {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addText($this->getName(), $this->getLabel())
			->setDefaultValue($this->getValue());
	}
}