<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Nette\Forms\Container,
	Nette\Forms\Controls\UploadControl,
	Extras\Types\Address;


class AdvancedUpload extends UploadControl {

	public $defaultParam;

	public function setDefaultParam($value) {
		$this->defaultParam = $value;
	}
	public function setValue($value) {
		debug('set', $value);
		if(is_array($value)) {
			if(isset($value['delete'])) {
				$this->value = FALSE;
				return $this;
			} else {
				$value = $value['file'];
			}
		}
		return parent::setValue($value);
	}

	public function getValue() {
		return $this->value;
	}

	public function getControl() {
		$control = parent::getControl();
		$id = $control->id;
		$name = $control->name;

		$control->name .= '[file]';
		$wrapper = Html::el('div')->class('upload-wrapper');

		$wrapper->add($control);
		if($this->defaultParam) {
			if($this->defaultParam->id) {
				$wrapper->add(Html::el('div')->class('uploaded-file')->add($this->defaultParam->id));
			}
		}
		$delete = Html::el('input')->type('checkbox')->value(1)->id($id . '-delete')->name($name . '[delete]');
		$wrapper->add($delete . ' Delete');
		return $wrapper;
	}

	public static function register()
	{
		Container::extensionMethod('addAdvancedUpload', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedUpload($label);
		});
	}

}