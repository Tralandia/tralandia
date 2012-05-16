<?php

namespace Extras\Forms\Controls;

use Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl,
	Nette\Utils\Html;


class AdvancedCheckBox extends BaseControl {

	/**
	 * @param  string  label
	 */
	public function __construct($label = NULL)
	{
		parent::__construct($label);
		$this->control->type = 'checkbox';
		$this->value = FALSE;
	}



	/**
	 * Sets control's value.
	 * @param  bool
	 * @return Checkbox  provides a fluent interface
	 */
	public function setValue($value)
	{
		$this->value = is_scalar($value) ? (bool) $value : FALSE;
		return $this;
	}



	/**
	 * Generates control's HTML element.
	 * @return Nette\Utils\Html
	 */
	public function getControl()
	{
		$control = parent::getControl()->checked($this->value);
		if($this->getOption('label')) {
			$control = Html::el('label')->add($control.$this->getOption('label'));
		}
		return $control;
	}

	/**
	 * Adds AdvancedCheckBox() method to Nette\Forms\Container
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedCheckBox', function (Container $_this, $name, $label = NULL) {
			return $_this[$name] = new AdvancedCheckBox($label);
		});
	}

}
