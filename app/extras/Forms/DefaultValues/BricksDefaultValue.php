<?php

namespace Extras\Forms\DefaultValues;

class BricksDefaultValue extends \Nette\Object {

	private $bricks;

	/**
	 * @param array $bricks
	 */
	public function __construct(array $bricks) {
		$this->bricks = $bricks;
	}

	public function getValues() {
		return $this->bricks;
	}

	public function getValue($id) {
		return isset($this->bricks[$id]) ? $this->bricks[$id] : NULL;
	}

	public function getStringValue() {
		return '';
	}

	public function __toString() {
		return (string) $this->getStringValue();
	}
}
