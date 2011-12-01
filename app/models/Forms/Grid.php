<?php

namespace Tra\Forms;

use Tra\Services;

class Grid extends Form {

	private $service;

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		
		$this->service = new Services\Rental;
		$this->service->prepareForm($this);
		
		$this->ajax(false);
		$this->addHidden('id');
		$this->addSubmit('save', 'Save');
	}
	
	public function onDataRecieved($form) {
		$values = $form->getPrepareValues($this->service);		

		$this->service->gridUpdate($form->getValues()->id, $values);
		
		/*
		debug($form);
		debug($form->getValues()->id);
		debug($form->getValues()->Rental);
		*/
    }

	public function onInvalidDataRecieved($form) {
//		debug($form);
    }
}
