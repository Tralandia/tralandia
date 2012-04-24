<?php

namespace AdminModule\Forms;

class Rental extends Form {

	private $sRental;

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$this->sRental = new Services\Rental;
		$this->sRental->prepareForm($this);
				
				
		$this->ajax(false);
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		$values = $form->getPrepareValues($this->sRental);		
		
		$this->sRental->create($values);
    }
}
