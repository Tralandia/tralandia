<?php

namespace FrontModule;

class SignPresenter extends BasePresenter {

	public function actionIn() {
		if($this->user->isLoggedIn()) {
			$this->redirect('Home:default');
		}
	}

	/**
	 * @return \BaseModule\Forms\Sign\In
	 */
	protected function createComponentSignIn()
	{
		$comp = new \BaseModule\Forms\Sign\In;
	
		return $comp;
	}

	public function actionOut() {
		$this->user->logout();
		$this->redirect('Home:default');
	}

}
