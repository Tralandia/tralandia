<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * Tinymce polozka masky
 */
class Tinymce extends Text {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addAdvancedTinymce($this->getName(), $this->getLabel())
			->setDefaultValue($this->getValue())
			->setDisabled($this->disabled);
	}
}