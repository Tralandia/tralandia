<?php

namespace Extras\FormMask\Items;

use Nette, Entity, Extras;

/**
 * Phrase polozka masky
 */
class ReadOnlyPhrase extends AdvancedPhrase {

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @param Extras\Environment
	 */
	public function __construct($name, $label, \Entity\BaseEntity $entity, \Environment\Environment $environment) {
		parent::__construct($name, $label, $entity, $environment);
		$this->setValueSetter(null);
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addReadOnlyPhrase($this->getName(), $this->getLabel())
			->setPhrase($this->getValue())
			->setDefaultLanguage($this->environment->getLanguage())
			->setLink(function(Entity\Phrase\Translation $translation) use($form) {
				return $form->getPresenter()->link('PhraseList:edit', array($translation->phrase->id, $translation->language->id));
			});
	}
}
