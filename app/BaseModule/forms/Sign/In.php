<?php

namespace BaseModule\Forms\Sign;

class In extends \BaseModule\Forms\BaseForm {

	public function __construct() {
		parent::__construct();

		$this->addText('login', 'Login:');
		$this->addPassword('password', 'Password');

		$this->addSubmit('signIn', 'SignIn');
		
		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	protected function buildForm() {
		
	}

	public function setDefaultsValues()
	{

	}

	public function onSuccess(In $form) {
		$values = $form->getValues();
		try {
			$this->getPresenter()->getUser()->setExpiration('+ 30 days', FALSE);
			$this->getPresenter()->getUser()->login($values->login, $values->password);
			$this->presenter->redirect('this');
		} catch(\Nette\Security\AuthenticationException $e) {
			$form->addError('#zle prihlasovacie udaje');
		}
	}

}
