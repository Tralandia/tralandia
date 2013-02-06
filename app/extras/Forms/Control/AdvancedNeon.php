<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Nette\Utils\Neon,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\TextBase;


class AdvancedNeon extends TextBase {

	public function __construct($label = NULL, $cols = NULL, $rows = NULL)
	{
		parent::__construct($label);
		$this->control->setName('textarea');
		$this->control->cols = $cols;
		$this->control->rows = $rows;
		$this->value = '';
	}


	public function setValue($value) {
		if(is_array($value)) {
			$this->value = Neon::encode($value);
		} else {
			$this->value = $value;
		}
		return $this;
	}

	public function getValue() {
		$value = Neon::decode($this->value);
		return $value;
	}

	public function getControl()
	{
		$control = parent::getControl();
		$control->setText($this->value);
		$output = Html::el('div')->class('neonOutput span6');
		return $control.$output;
	}


	/**
	 * Adds addCheckboxList() method to Nette\Forms\Container
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedNeon', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedNeon($label);
		});
	}

}