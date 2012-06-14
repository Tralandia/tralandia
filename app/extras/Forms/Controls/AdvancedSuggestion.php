<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Tra\Utils\Strings,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl,
	Extras\Types\Address;


class AdvancedSuggestion extends BaseControl {

	// public function setValue($value) {
	// 	if(!is_array($value)) $value = $value->toArray();
	// 	$this->value = $value;
	// 	return $this;
	// }

	// public function getValue()
	// {
	// 	return is_array($this->value) ? $this->value : NULL;
	// }


	public function getControl() {
		$value = $this->value;
		$control = parent::getControl();
		$fakeInput = Html::el('input')
						->class($control->class)
						->addClass('typeahead')
						->addAttributes(array(
							'data-serviceList' => $this->getOption('serviceList'),
							'data-property' => $this->getOption('property'),
							'data-language' => 0,
							'data-default-id' => $value,
							'data-default-value' => $value,
							'value' => $value,
						));
		if($value > 0) {
			$control->value = $value;
			$serviceName = $this->getOption('serviceName');
			$value = $serviceName::get($this->value);
			if($value) {
				$value = $value->{$this->getOption('property')};
				$language = $this->getForm()->getEnvironment()->getLanguage();
				if($value instanceof \Entity\Dictionary\Phrase) {
					$value = \Service\Dictionary\Phrase::get($value)->getTranslation($language, true);
					$fakeInput->addAttributes(array(
						'data-language' => $language->id,
						'data-default-value' => $value,
						'value' => $value,
					));
				}
			}
		}

		return $fakeInput.$control->addClass('hide');
	}

	public static function register()
	{
		Container::extensionMethod('addAdvancedSuggestion', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedSuggestion($label);
		});
	}

}