<?php

namespace Extras\FormMask\Items;

use Nette, Service, Entity;

/**
 * Phrase polozka masky
 */
class Phrase extends Base {

	/** @var Service\Dictionary\Phrase */
	protected $phraseService;

	/** @var Entity\Dictionary\Language */
	protected $language;

	/**
	 * @param string
	 * @param string
	 * @param Service\Dictionary\Phrase
	 */
	public function __construct($name, $label, Service\Dictionary\Phrase $phraseService, Entity\Dictionary\Language $language) {
		parent::__construct($name, $label);
		$this->phraseService = $phraseService;
		$this->language = $language;
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		debug($this->phraseService);
		debug($this->language);
		debug($this->phraseService->getTranslateValue($this->language));

		return $this->phraseService->getTranslateValue($this->language);
		
		return $form->addText($this->getName(), $this->getLabel())
			->setDefaultValue($this->phraseService->getTranslateValue($this->getValue()));
	}
}