<?php

namespace Extras\Config\Form;

use Nette;

abstract class Field extends Nette\Object {

	/** @var string */
	protected $name;
	
	/** @var string */
	protected $label;

	/** @var array */
	protected $options = array();

	/** @var array */
	protected $validators = array();

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