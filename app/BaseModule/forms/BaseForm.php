<?php

namespace BaseModule\Forms;

use Nette;
use \Nette\ComponentModel\Container;
use \Nette\Forms\IControl;
use \Nette\Forms\ISubmitterControl;

abstract class BaseForm extends \CoolForm {

	/**
	 * @var array of function (BaseForm $form, Nette\Application\UI\Presenter $presenter)
	 */
	public $onAttached = array();

	/**
	 * Returns the formatted values submitted by the form.
	 * @param  bool  return values as an array?
	 * @return Nette\ArrayHash|array
	 */
	public function getFormattedValues($asArray = FALSE)
	{
		$values = $asArray ? array() : new \Nette\ArrayHash;
		foreach ($this->getComponents() as $name => $control) {
			if ($control instanceof IControl && !$control->isDisabled() && !$control->isOmitted() && !$control instanceof ISubmitterControl) {
				if (method_exists($control, 'getFormattedValue')) {
					$values[$name] = $control->getFormattedValue();
				} else {
					$values[$name] = $control->getValue();
				}
			} elseif ($control instanceof Container) {
				if (method_exists($control, 'getFormattedValues')) {
					$values[$name] = $control->getFormattedValues($asArray);
				} else {
					$values[$name] = $control->getValues($asArray);
				}
			}
		}
		return $values;
	}

	/**
	 * Returns the formatted values submitted by the form.
	 * @param  bool  return values as an array?
	 * @return Nette\ArrayHash|array
	 */
	public function getValidFormattedValues($asArray = FALSE)
	{
		$values = $asArray ? array() : new \Nette\ArrayHash;
		foreach ($this->getComponents() as $name => $control) {
			if ($control instanceof IControl && !$control->isDisabled() && !$control->isOmitted() && !$control instanceof ISubmitterControl) {
				if(!$control->hasErrors()) {
					if (method_exists($control, 'getValidFormattedValues')) {
						$values[$name] = $control->getValidFormattedValues();
					} else {
						$values[$name] = $control->getValue();
					}
				}
			} elseif ($control instanceof Container) {
				if($control->isValid()) {
					if (method_exists($control, 'getValidFormattedValues')) {
						$values[$name] = $control->getValidFormattedValues($asArray);
					} else {
						$values[$name] = $control->getValues($asArray);
					}
				}
			}
		}
		return $values;
	}

	protected function attached($parent)
	{
		parent::attached($parent);

		if (!$parent instanceof Nette\Application\UI\Presenter) {
			return;
		}

		//$this->setTranslator($this->presenter->context->getByType('Nette\Localization\ITranslator'));

		$this->onAttached($this, $parent);
	}

}
