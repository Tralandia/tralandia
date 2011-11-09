<?php

namespace Forms;

use Nette\Forms\Form,
	Nette\Application as NA,
	Nette\Diagnostics\Debugger,
	Tra\Services;

class Rental extends \CoolForm {

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$s = new Services\Rental;
		$s->prepareFormRental($this);
				
		$this->ajax(false);
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($s, 'prepareData');
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		//$values = $service->prepareData($form->getValues());
		$values = $form->getValues();
		
		debug($form['Rental']['country']->getValue());
		debug($values);
    }
}
