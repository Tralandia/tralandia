<?php

namespace Extras\FormMask\Items;

use Nette, Service;

/**
 * Phrase polozka masky
 */
class Phrase extends Base {

	protected $phraseService;

	/**
	 * @param string
	 * @param string
	 * @param Service\Dictionary\Phrase
	 */
	public function __construct($name, $label, Service\Dictionary\Phrase $phraseService) {
		parent::__construct($name, $label);
		$this->phraseService = $phraseService;
	}

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