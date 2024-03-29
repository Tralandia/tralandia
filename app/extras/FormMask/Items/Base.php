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

	/** @var Extras\Callback */
	protected $valueUnSetter;

	/** @var array */
	protected $validators = array();

	/** @var bool */
	protected $disabled = false;

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
	 * Vrati zmemenu hodnotu itemu
	 * @return mixed
	 */
	public function getUpdatedValue() {
		return $this->getValue();
	}

	/**
	 * Nastavi hodnotu itemu
	 * @param mixed
	 * @return mixed
	 */
	public function setValue($value) {
		if($value === NULL) {
			$valueUnSetter = $this->getValueUnSetter();
			if ($valueUnSetter) {
				return $valueUnSetter->invoke($value);
			}
		}
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
	public function setValueGetter(Extras\Callback $valueGetter = null) {
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
	public function setValueSetter(Extras\Callback $valueSetter = null) {
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

	public function setValueUnSetter(Extras\Callback $valueUnSetter = null) {
		$this->valueUnSetter = $valueUnSetter;
		return $this;
	}

	/**
	 * Getter settera hodnot
	 * @return Extras\Callback
	 */
	public function getValueUnSetter() {
		return $this->valueUnSetter;
	}

	/**
	 * @return array
	 */
	public function getValidators() {
		return $this->validators;
	}

	/**
	 * @param array
	 * @return Base
	 */
	public function setValidators($validators) {
		 $this->validators = $validators;
		 return $this;
	}

	/**
	 * Nastavi ci je item didabled
	 * @param mixed
	 * @return Base
	 */
	public function setDisabled($bool = true) {
		$this->disabled = $bool;
		return $this;
	}

	/**
	 * Vrati nazov setter metody
	 * @param string
	 * @return string
	 */
	protected function setterMethodName($name) {
		return 'set' . ucfirst($name);
	}

	/**
	 * Vrati nazov setter metody
	 * @param string
	 * @return string
	 */
	protected function unSetterMethodName($name) {
		return 'unset' . ucfirst($name);
	}

	/**
	 * Vrati nazov getter metody
	 * @param string
	 * @return string
	 */
	protected function getterMethodName($name) {
		return 'get' . ucfirst($name);
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		$control = $form->addText($this->getName(), $this->getLabel());
		$control->setDefaultValue($this->getValue());
		$control->setDisabled($this->disabled);

		foreach ($this->validators as $validator) {
			call_user_func_array(array($control, $validator->method), $validator->params);
		}

		return $control;
	}

	/**
	 * Spracovanie dat z formulara
	 * @param Nette\Forms\Form
	 */
	public function process(Nette\Forms\Form $form) {
		if ($this->getValueSetter()) {
			$value = $form->getComponent($this->getName())->getValue();
			$this->setValue($value);
		}
	}
}
