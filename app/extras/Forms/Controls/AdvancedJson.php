<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl;


class AdvancedJson extends BaseControl {

	protected $structure;


	public function __construct($label = NULL, array $structure = NULL) {
		parent::__construct($label);
		if($structure !== NULL) {
			$this->setStructure($structure);
		}
	}

	public function setStructure($structure) {
		$this->structure = $structure;
	}

	public function getValue()
	{
		return is_array($this->value) ? $this->value : NULL;
	}


	public function getControl() {
		$control = parent::getControl();
		$values = $this->getValue();
		$tree = $this->generateCildren($control, $this->structure, $values);
		return $tree;
	}

	protected function generateCildren($control, $parent, $values = NULL) {
		$container = Html::el('ul')->class('unstyled');
		foreach ($parent as $key => $value) {
			$itemContainer = Html::el('li');
			$controlCloned = clone $control;
			$controlCloned->name .= "[$key]";
			if(is_array($value)) {
				$children = $this->generateCildren($controlCloned, $value, $values ? Arrays::get($values, $key, NULL) : NULL);
				$container->add($itemContainer->add($children));
			} else {
				$id = $controlCloned->id;
				$controlCloned->id = $id . '-' . $key;
				$controlCloned->placeholder = $key;
				if(isset($values[$key])) $controlCloned->value = $values[$key];
				$container->add($itemContainer->add($controlCloned));
			}
		}
		return $container;
	}

	/**
	 * Adds addCheckboxList() method to Nette\Forms\Container
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedJson', function (Container $_this, $name, $label, array $structure = NULL) {
			return $_this[$name] = new AdvancedJson($label, $structure);
		});
	}

}