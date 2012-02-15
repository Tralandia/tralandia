<?php

namespace Tra\Forms\User;

use Tra\Services,
	Tra\Forms;

class Registration extends Forms\Form {

	private $sUser;

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$this->sUser = new Services\User;
		$this->sUser->prepareRegistrationForm($this);
			
		$this->ajax(false);
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		$values = $form->getPrepareValues($this->sUser);		
		
		$this->sUser->create($values);
    }
}
