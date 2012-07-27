<?php

namespace FrontModule;

class SignPresenter extends BasePresenter {

	public function actionIn() {
		if($this->user->isLoggedIn()) {
			$this->redirect('Home:default');
		}

		$form = $this->getComponent('signIn');
		$form->setDefaults(array(
			'backlink' => $this->getParam('backlink'),
		));
	}

	/**
	 * @return \BaseModule\Froms\SignIn
	 */
	protected function createComponentSignIn($name)
	{
		$comp = new \BaseModule\Forms\Sign\In($this, $name);
	
		return $comp;
	}

	public function actionOut() {
		$this->user->logout();
		$this->redirect('Home:default');
	}

}
