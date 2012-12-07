<?php
namespace Extras\FormMask\Items;

use Nette;

/**
 * Boolean polozka masky
 * 
 * @author Branislav Vaculčiak
 */
class YesNo extends Text {

	/**
	 * Vrati vsetky polozky
	 * @return mixed
	 */
	public function getItems() {
		return array(
			0 => 'No',
			1 => 'Yes'
		);
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addSelect($this->getName(), $this->getLabel(), $this->getItems())
			->setDefaultValue($this->getValue())
			->setDisabled($this->disabled);
	}
}