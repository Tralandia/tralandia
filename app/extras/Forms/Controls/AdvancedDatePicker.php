<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Extras\Types\Address;


class AdvancedDatePicker extends \DateInput {

	public function setDateType($type) {
		if (!isset(self::$formats[$type])) {
			throw new \InvalidArgumentException("invalid type '$type' given.");
		}
		$this->control->type = $this->type = $type;
		$this->control->data('dateinput-type', $type);
	}


	public static function register() {
		$class = __CLASS__;
		\Nette\Forms\Container::extensionMethod('addAdvancedDatePicker', function (\Nette\Forms\Container $form, $name, $label = null, $type = 'datetime-local') use ($class) {
			$component = new $class($label, $type);
			$form->addComponent($component, $name);
			return $component;
		});
		\Nette\Forms\Rules::$defaultMessages[':dateInputRange'] = \Nette\Forms\Rules::$defaultMessages[\Nette\Forms\Form::RANGE];
		\Nette\Forms\Rules::$defaultMessages[':dateInputValid'] = 'Please enter a valid date.';
	}

}