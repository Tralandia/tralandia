<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {


	public function actionTest() {
		
	}

	public function actionList() {

		$dic = new \Tra\Services\Dictionary;
		$dic->run();
		debug($dic);

		/*
		$country = new \Tra\Services\Country(1);
		$country->iso = 'sk';
		$country->save();


		$user = new \Tra\Services\User;
		$user->country = $country;
		$user->login = 'waccoTEST';
		$user->active = true;
		$user->password = 'testik';
		$user->save();
		*/
	}
	
	public function renderAdd() {
		$this->template->form = $this->getComponent('form');
	}

	protected function createComponentForm($name) {
		return new \Tra\Forms\User($this, $name);
	}

}
