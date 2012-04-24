<?php

namespace AdminModule\Forms;

class AdminForm extends Form {

	protected $service;
	protected $reflector;

	public function __construct($parent, $name, $reflector, $service) {
		parent::__construct($parent, $name);

		$this->reflector = $reflector;
		$this->service = $service;

		$reflector->extend($this, $this->reflector->getFormMask());
		
		$this->ajax(false);
		$this->addSubmit('save', 'Save');
		
		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function onSuccess(AdminForm $form) {
		$id = $this->presenter->getParam('id');
		$values = $this->reflector->getPrepareValues($form);

		//TODO : ainak zistit ze editujem, najlepsie so servisy
		if ($id) {
			// EDIT
			$this->service->updateFormData($this->reflector->getFormMask(), $values);
		} else {
			// ADD
			$this->service->create($this->reflector->getFormMask(), $values);
		}
	}

}
