<?php

namespace BaseModule\Forms\Sign;

use Nette;

class InForm extends \BaseModule\Forms\BaseForm {

	protected function buildForm() {
		$this->addText('login', 'Login:');
		$this->addPassword('password', 'Password');

		$this->addSubmit('signIn', 'SignIn');

		$this->onSuccess[] = callback($this, 'onSuccess');
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

interface IInFormFactory {
	/**
	 * @return InForm
	 */
	public function create();
}
