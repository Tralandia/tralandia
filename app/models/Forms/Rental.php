<?php

namespace Forms;

use Nette\Forms\Form,
	Nette\Application as NA,
	Nette\Diagnostics\Debugger,
	\Services;

class Rental extends \CoolForm {

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$s = new Services\Rental;
		$s->prepareFormRental($this);
				
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($this, 'prepareData');
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		$values = $form->getValues();
		
		debug($values);
    }
}
