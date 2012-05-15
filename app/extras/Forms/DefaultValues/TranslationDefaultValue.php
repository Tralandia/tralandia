<?php

namespace Extras\Forms\DefaultValues;

class TranslationDefaultValue extends \Nette\Object {

	private $translation;

	/**
	 * @param \Service\Dictionary\Translation|\Entity\Dictonary\Translation $translation
	 */
	public function __construct($translation) {
		if($translation instanceof \Service\Dictionary\Translation || $translation instanceof \Entity\Dictionary\Translation) {
			$this->translation = $translation;
		} else {
			throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
		}
	}

	public function getPhraseId() {
		return $this->translation->phrase->id;
	}

	public function getStringValue() {
		return $this->translation->translation;
	}

	public function __toString() {
		return (string) $this->getStringValue();
	}
}
