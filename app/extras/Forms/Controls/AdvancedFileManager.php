<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Nette\Forms\Container,
	Extras\Forms\Controls\AdvancedControl;


class AdvancedFileManager extends AdvancedControl
{
	/** @var Nette\Utils\Html  separator element template */
	protected $separator;

	/** @var Nette\Utils\Html  container element template */
	protected $container;

	/** @var array */
	protected $items = array();

	/**
	 * @param string $label
	 * @param array $items  Options from which to choose
	 */
	public function __construct($label, array $items = NULL)
	{
		parent::__construct($label);
		$this->control->type = 'checkbox';
		$this->container = Html::el();
		$this->separator = Html::el('br');
		if ($items !== NULL) {
			$this->setItems($items);
		}
	}



	/**
	 * Returns selected radio value. NULL means nothing have been checked.
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return is_array($this->value) ? $this->value : NULL;
	}



	/**
	 * Sets options from which to choose.
	 *
	 * @param array $items
	 * @return CheckboxList  provides a fluent interface
	 */
	public function setItems(array $items)
	{
		$this->items = $items;
		return $this;
	}



	/**
	 * Returns options from which to choose.
	 *
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * Returns separator HTML element template.
	 *
	 * @return Nette\Utils\Html
	 * @return Nette\Web\Html
	 */
	public function getSeparatorPrototype()
	{
		return $this->separator;
	}



	/**
	 * Returns container HTML element template.
	 *
	 * @return Nette\Utils\Html
	 */
	public function getContainerPrototype()
	{
		return $this->container;
	}



	/**
	 * Generates control's HTML element.
	 *
	 * @param mixed $key  Specify a key if you want to render just a single checkbox
	 * @return Nette\Utils\Html
	 */
	public function getControl($key = NULL)
	{
		if ($key === NULL) {
			$container = clone $this->container;
			$separator = (string) $this->separator;

		} elseif (!isset($this->items[$key])) {
			return NULL;
		}

		$control = parent::getControl();
		$control->name .= '[]';
		$id = $control->id;
		$counter = -1;
		$values = $this->value === NULL ? NULL : (array) $this->getValue();

		$brickTemplate = Html::el('div')->addClass('brick btn btn-info');
		if($values) {
			foreach ($values as $k => $value) {
				$brick = clone $brickTemplate;
				$brick->add(Html::el('span')->setText($value['value']));

				if($this->inlineEditing) {
					$brick->add(Html::el('a')->setText('InlineEditing'));
				}

				$container->add((string) $brick);
			}
		}
		if($this->inlineCreating) {
			$brick = clone $brickTemplate;
			$brick->add(Html::el('a')->setText('InlineCreating'));
			$container->add($brick);
		}
		$container->add(Html::el('div')->setId('managerUploader')->add(Html::el('noscript')->setText('Please enable JavaScript to use file uploader.')));


		return $container;
	}



	/**
	 * Generates label's HTML element.
	 *
	 * @return Html
	 */
	public function getLabel($caption = NULL)
	{
		$label = parent::getLabel($caption);
		$label->for = NULL;
		return $label;
	}



	/**
	 * Filled validator: has been any checkbox checked?
	 *
	 * @param CheckboxList $control
	 * @return bool
	 */
	public static function validateChecked(CheckboxList $control)
	{
		return $control->getValue() !== NULL;
	}



	/**
	 * Adds addCheckboxList() method to Nette\Forms\Container
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedFileManager', function (Container $_this, $name, $label, array $items = NULL) {
			return $_this[$name] = new AdvancedFileManager($label, $items);
		});
	}

}