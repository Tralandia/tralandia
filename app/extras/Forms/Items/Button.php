<?php

namespace Extras\Forms\Items;

use Nette;

/**
 * Button polozka masky
 */
class Button extends Base {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addButton($this->getName(), $this->getLabel());
	}
}