<?php

namespace AdminModule\Forms;

class User extends Form {

	private $sUser;

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		//$this->sUser = new Services\User;
		$this->sUser->prepareForm($this);
			
		$this->ajax(false);
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		$values = $form->getPrepareValues($this->sUser);		
		
		$this->sUser->create($values);
    }
}
