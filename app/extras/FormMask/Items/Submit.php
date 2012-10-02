<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * Submit polozka masky
 */
class Submit extends Base {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addSubmit($this->getName(), $this->getLabel());
	}
}