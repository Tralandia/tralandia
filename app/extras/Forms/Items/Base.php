<?php

namespace Extras\Forms\Items;

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
		return call_user_func($this->getValueGetter());
	}

	/**
	 * Setter gettera hodnost
	 * @param array
	 * @return Base
	 */
	public function setValueGetter(array $valueGetter) {
		$this->valueGetter = $valueGetter;
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
	 * @return Base
	 */
	public function setValueSetter(array $valueSetter) {
		$this->valueSetter = $valueSetter;
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