<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * Hidden polozka masky
 */
class Hidden extends Base {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addHidden($this->getName())
			->setDefaultValue($this->getValue());
	}
}