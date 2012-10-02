<?php

namespace Extras\FormMask\Items;

use Nette;

/**
 * Abstraktna trieda poloziek masky
 */
abstract class Base {

	/** @var string */
	protected $name;

	/** @var string */
	protected $label = null;

	/** @var array */
	protected $valueGetter;

	/** @var array */
	protected $valueSetter;

	/** @var array */
	protected $valueGetterParams;

	/** @var array */
	protected $valueSetterParams;

	/**
	 * @param string
	 * @param string
	 */
	public function __construct($name, $label = null) {
		$this->name = $name;
		$this->label = $label;
	}

	/**
	 * Getter nazvu
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Getter labelu
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Vrati hodnotu itemu
	 * @return mixed
	 */
	public function getValue() {
		return call_user_func_array($this->getValueGetter(), $this->valueGetterParams);
	}

	/**
	 * Setter gettera hodnost
	 * @param array
	 * @param array
	 * @return Base
	 */
	public function setValueGetter(array $valueGetter, array $params = null) {
		$this->valueGetter = $valueGetter;
		$this->valueGetterParams = $params;
		return $this;
	}

	/**
	 * Getter gettera hodnot
	 * @return array
	 */
	public function getValueGetter() {
		return $this->valueGetter;
	}

	/**
	 * Setter settera hodnost
	 * @param array
	 * @param array
	 * @return Base
	 */
	public function setValueSetter(array $valueSetter, array $params = null) {
		$this->valueSetter = $valueSetter;
		$this->valueSetterParams = $params;
		return $this;
	}

	/**
	 * Getter settera hodnot
	 * @return array
	 */
	public function getValueSetter() {
		return $this->valueSetter;
	}

	/**
	 * Vrati kontrol formulara
	 * @return Nette\Forms\IControl
	 */
	public function getFormControl() {
		return $this->form->getComponent($this->getName());
	}
}