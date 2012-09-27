<?php

namespace Extras\Forms\Items;

use Nette;

/**
 * RadioList polozka masky
 */
class RadioList extends Select {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addRadioList($this->getName(), $this->getLabel(), $this->getItems())
			->setDefaultValue($this->getValue());
	}
}