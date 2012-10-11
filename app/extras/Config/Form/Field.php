<?php

namespace Extras\Config\Form;

use Nette;

abstract class Field extends Nette\Object {

	/** @var string */
	protected $name;
	
	/** @var string */
	protected $label;

	/**
	 * @param string
	 * @param string
	 */
	public function __construct($name, $label) {
		$this->name = $name;
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return strtolower(substr(get_class($this), strrpos(get_class($this), '\\')+1));
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;	
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return "";
	}

	/**
	 * @return string
	 */
	public function getClass() {
		return "";
	}
}