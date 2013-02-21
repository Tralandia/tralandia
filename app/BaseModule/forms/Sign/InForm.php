<?php

namespace BaseModule\Forms\Sign;

use Nette;

class InForm extends \BaseModule\Forms\BaseForm {

	protected function buildForm() {
		$this->addText('login', 'Login:');
		$this->addPassword('password', 'Password');

		$this->addSubmit('submit', 'SignIn');

		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function setDefaultsValues()
	{

	}

	public function onSuccess(InForm $form) {
		$values = $form->getValues();
		try {
			$user = $this->getPresenter()->getUser();
			$user->setExpiration('+ 30 days', FALSE);
			$user->login($values->login, $values->password);
			if($homepage = $user->getIdentity()->homepage){
				$this->presenter->redirect($homepage);
			}
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
