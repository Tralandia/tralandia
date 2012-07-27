<?php

namespace AdminModule\Forms;

class Grid extends Form {

	private $service;

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		
		//$this->service = $this->getParentService();
		//$this->service->prepareForm($this);
		
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
