<?php

namespace Extras\Config;

use Nette;

class Field extends Nette\Object {

	/** @var string */
	public $name;
	
	/** @var string */
	public $label;

	/** @var string */
	public $type;

	/** @var array */
	public $options = array();

	/** @var array */
	public $validators = array();

	/**
	 * @param string
	 * @param string
	 * @param string
	 */
	public function __construct($name, $label, $type) {
		$this->name = $name;
		$this->label = $label;
		$this->type = $type;
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

	/**
	 * @return array
	 */
	public function getValidators() {
		return $this->validators;
	}

	/**
	 * @param string
	 * @param array
	 * @return Field
	 */
	public function addValidator($method, array $params) {
		 $this->validators[] = (object)array(
			'method' => $method,
			'params' => $params
		 );
		 return $this;
	}

	/**
	 * @param string
	 * @param array
	 * @return Field
	 */
	public function setOptions($section, array $params) {
		 $this->options[$section] = $params;
		 return $this;
	}
}