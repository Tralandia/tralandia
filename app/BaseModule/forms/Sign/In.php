<?php

namespace BaseModule\Forms\Sign;

class In extends \BaseModule\Forms\BaseForm {

	public function __construct($parent, $name) {
		parent::__construct($parent, $name);

		$this->addText('login', 'Login:');
		$this->addPassword('password', 'Password');
		$this->addHidden('backlink');

		$this->addSubmit('signin', 'SignIn');
		
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
			if($values->backlink) {
				$this->presenter->restoreRequest($values->backlink);
			} else if(isset($this->presenter->user->identity->homePage)) {
				$this->presenter->redirect($this->presenter->user->identity->homePage);
			}
			$this->presenter->redirect('this');
		} catch(\Nette\Security\AuthenticationException $e) {

		}
	}

}
