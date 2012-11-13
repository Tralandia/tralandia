<?php

namespace Extras\FormMask\Items;

use Nette, Entity, Extras;

/**
 * Phrase polozka masky
 */
class AdvancedPhrase extends Text {

	/** @var Extras\Environment */
	protected $environment;

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @param Extras\Environment
	 */
	public function __construct($name, $label, Extras\Models\Entity\IEntity $entity, Extras\Environment $environment) {
		parent::__construct($name, $label, $entity);
		$this->environment = $environment;
		$this->setValueSetter(new Extras\Callback($this, 'phraseSetter', array($entity->{$this->name})));
	}

	/**
	 * Callback na ulozenie dat do frazy
	 * @param Entity\Phrase\Phrase
	 * @param array
	 */
	public function phraseSetter(Entity\Phrase\Phrase $phrase, array $values) {
		foreach ($phrase->getTranslations() as $translation) {
			if (isset($values[$translation->language->iso]) && $translation->translation != $values[$translation->language->iso]) {
				$translation->translation = $values[$translation->language->iso];
			}
		}
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		$control = $form->addAdvancedPhrase($this->getName(), $this->getLabel());
		$control->setPhrase($this->getValue());

		foreach ($this->validators as $validator) {
			call_user_func_array(array($control, $validator->method), $validator->params);
		}

		return $control;
	}
}