<?php

namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Abstraktna trieda poloziek masky
 */
abstract class Base {

	/** @var string */
	protected $name;

	/** @var string */
	protected $label;

	/** @var Extras\Callback */
	protected $valueGetter;

	/** @var Extras\Callback */
	protected $valueSetter;

	/**
	 * @param string
	 * @param string
	 */
	public function __construct($name, $label) {
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
		if (!is_callable($this->getValueGetter())) {
			throw new Nette\InvalidStateException("Nebol zadaný callback gettera hodnot.");
		}
		return $this->getValueGetter()->invoke();
	}

	/**
	 * Nastavi hodnotu itemu
	 * @param mixed
	 * @return mixed
	 */
	public function setValue($value) {
		if (!is_callable($this->getValueSetter())) {
			throw new Nette\InvalidStateException("Nebol zadaný callback settera hodnot.");
		}
		return $this->getValueSetter()->invoke($value);
	}

	/**
	 * Setter gettera hodnost
	 * @param Extras\Callback
	 * @return Base
	 */
	public function setValueGetter(Extras\Callback $valueGetter) {
		$this->valueGetter = $valueGetter;
		return $this;
	}

	/**
	 * Getter gettera hodnot
	 * @return Extras\Callback
	 */
	public function getValueGetter() {
		return $this->valueGetter;
	}

	/**
	 * Setter settera hodnost
	 * @param Extras\Callback
	 * @return Base
	 */
	public function setValueSetter(Extras\Callback $valueSetter) {
		$this->valueSetter = $valueSetter;
		return $this;
	}

	/**
	 * Getter settera hodnot
	 * @return Extras\Callback
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