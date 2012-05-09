<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl;


class AdvancedTable extends BaseControl {

	protected $rows;

	protected $columns;


	public function __construct($label = NULL, array $columns = NULL, $rows = NULL) {
		parent::__construct($label);
		if($columns !== NULL) {
			$this->setColumns($columns);
		}
		if($rows !== NULL) {
			$this->setRows($rows);
		}
	}

	public function setRows($rows) {
		$this->rows = $rows;
	}
	
	public function setColumns($columns) {
		$this->columns = $columns;
	}

	public function getValue() {
		return is_array($this->value) ? $this->value : NULL;
	}


	public function getControl() {
		$control = parent::getControl();
		$values = $this->getValue();

		$table = Html::el('table')->class('table');
		$tr = Html::el('tr');
		foreach ($this->columns as $key => $value) {
			$th = Html::el('th')->add($value);
			$tr->add($th);
		}
		$table->add($tr);

		if($this->rows > 0) {
			for($i = 0; $i < $this->rows; $i++) {
				$tr = Html::el('tr');
				foreach ($this->columns as $key => $value) {
					$clonedControl = clone($control);
					$clonedControl->name .= '['.$i.']['.$key.']';
					if(isset($values[$i][$key])) $clonedControl->value = $values[$i][$key];
					$td = Html::el('td')->add($clonedControl);
					$tr->add($td);
				}
				$table->add($tr);
			}
			
		}
		return $table;
	}


	/**
	 * Adds addAdvancedTable() method to Nette\Forms\Container
	 */
	public static function register() {
		Container::extensionMethod('addAdvancedTable', function (Container $_this, $name, $label, array $columns = NULL, $rows = NULL) {
			return $_this[$name] = new AdvancedTable($label, $columns, $rows);
		});
	}

}