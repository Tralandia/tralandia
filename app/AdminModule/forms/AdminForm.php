<?php

namespace AdminModule\Forms;

class AdminForm extends Form {

	protected $service;
	protected $reflector;

	public function __construct($parent, $name, $reflector, $service) {
		parent::__construct($parent, $name);

		$this->reflector = $reflector;
		$this->service = $service;

		$reflector->getPhraseAssociations();

		$reflector->extend($this, $this->reflector->getFormMask($this->service));

		//$this->addAdvancedFileManager('upload', 'File manager');		
		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function onSuccess(AdminForm $form) {
		$id = $this->presenter->getParam('id');
		$values = $this->reflector->getPrepareValues($form);

		if ($this->service->id) {
			// EDIT
			$this->service->updateFormData($this->reflector->getFormMask($this->service), $values);
		} else {
			// ADD
			$this->service->create($this->reflector);
			$this->service->updateFormData($this->reflector->getFormMask($this->service), $values);
		}

		$this->flashMessage('A je to!');
		if($this->getPresenter()->isAjax()) {
			$this->getPresenter()->payload->invalidateParent = true;
		}
	}

}
