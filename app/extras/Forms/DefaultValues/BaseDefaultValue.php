<?php


namespace Extras\Forms\DefaultValues;

abstract class BaseDefaultValue extends \Nette\Object {

	abstract public function getStringValue();

	public function __toString() {
		return (string) $this->getStringValue();
	}
}
