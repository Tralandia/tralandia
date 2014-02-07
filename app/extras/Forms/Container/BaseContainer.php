<?php

namespace Extras\Forms\Container;

use Nette;

use \Nette\ComponentModel\Container;
use \Nette\Forms\IControl;
use \Nette\Forms\ISubmitterControl;

abstract class BaseContainer extends Nette\Forms\Container
{
	abstract public function getMainControl();

	public function getDescription()
	{
		return $this->getMainControl()->getOption('description');
	}

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
				if (method_exists($control, 'getValidFormattedValues')) {
					$values[$name] = $control->getValidFormattedValues();
				} else {
					$values[$name] = $control->getValue();
				}
			} elseif ($control instanceof Container) {
				if (method_exists($control, 'getValidFormattedValues')) {
					$values[$name] = $control->getValidFormattedValues($asArray);
				} else {
					$values[$name] = $control->getValues($asArray);
				}
			}
		}
		return $values;
	}

	public function getValidFormattedValues($asArray = FALSE)
	{
		$values = $asArray ? array() : new \Nette\ArrayHash;
		foreach ($this->getComponents() as $name => $control) {
			if ($control instanceof IControl && !$control->isDisabled() && !$control->isOmitted() && !$control instanceof ISubmitterControl) {
				if(!$control->hasErrors()) {
					if (method_exists($control, 'getFormattedValue')) {
						$values[$name] = $control->getFormattedValue();
					} else {
						$values[$name] = $control->getValue();
					}
				}
			} elseif ($control instanceof Container) {
				if($control->isValid()) {
					if (method_exists($control, 'getFormattedValues')) {
						$values[$name] = $control->getFormattedValues($asArray);
					} else {
						$values[$name] = $control->getValues($asArray);
					}
				}
			}
		}
		return $values;
	}

}
