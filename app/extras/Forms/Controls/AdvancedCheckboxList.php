<?php
/**
 * Cloned RadioList from Nette Framework distribution. Instead of radios use
 * checkboxes.
 *
 * @copyright  Copyright (c) 2004, 2011 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://addons.nettephp.com/cs/checkboxlist
 * @package    Nette\Extras
 */

namespace Extras\Forms\Controls;

use Nette\Utils\Html,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl;



/**
 * CheckboxList
 *
 * @author    David Grudl
 * @author    Jan Vlcek
 * @author    Filip Procházka
 * @copyright Copyright (c) 2004, 2011 David Grudl
 * @package   Nette\Extras
 */
class AdvancedCheckBoxList extends BaseControl {
	/** @var Nette\Utils\Html  separator element template */
	protected $separator;

	/** @var Nette\Utils\Html  container element template */
	protected $container;

	/** @var array */
	protected $items = array();

	public $defaultParam;

	public function setDefaultParam($value) {
		$this->defaultParam = $value;
	}


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
		$counter = 0;
		$values = $this->value === NULL ? NULL : (array) $this->getValue();
		$label = Html::el('label')->addClass('checkbox '.$this->getOption('columnClass'));

		foreach ($this->items as $k => $val) {
			$counter++;
			if ($key !== NULL && $key != $k) continue; // intentionally ==

			$control->id = $label->for = $id . '-' . $counter;
			$control->checked = (count($values) > 0) ? in_array($k, $values) : false;
			$control->value = $k;

			if ($val instanceof Html) {
				$label->setHtml($val);
			} else {
				$label->setText($this->translate($val));
			}

			$buttonGroup = Html::el('div')->addClass('btn-group pull-right');
			if($this->getOption('inlineEditing')) {
				$inlineEditing = $this->getOption('inlineEditing');
				$buttonGroup->add($inlineEditing->href($inlineEditing->href->setParameter('id', $k)));
			}
			if($this->getOption('inlineDeleting')) {
				$inlineDeleting = $this->getOption('inlineDeleting');
				$buttonGroup->add($inlineDeleting->href($inlineDeleting->href->setParameter('id', $k)));
			}
			$label->add($buttonGroup);

			$labelHtml = $label->getHtml();

			if ($key !== NULL) {
				return (string) $label->setHtml($control.$labelHtml);
			}

			$container->add((string) $label->setHtml($control.$labelHtml));

		}
		if($this->getOption('inlineCreating')) {
			$container->add(Html::el('div')->add($this->getOption('inlineCreating'))->addClass($this->getOption('columnClass')));
		}

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
		$label->class = 'span12';
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
		Container::extensionMethod('addAdvancedCheckBoxList', function (Container $_this, $name, $label, array $items = NULL) {
			return $_this[$name] = new AdvancedCheckBoxList($label, $items);
		});
	}

}