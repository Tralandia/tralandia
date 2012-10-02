<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * TextArea polozka masky
 */
class TextArea extends Base {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addTextArea($this->getName(), $this->getLabel())
			->setDefaultValue($this->getValue());
	}
}