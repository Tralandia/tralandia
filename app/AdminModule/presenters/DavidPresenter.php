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

		$country = new \Tra\Services\Country(1);

		debug($country);

		return;

		$user = new \Tra\Services\User;
		$user->country_id = $country;
		$user->login = 'waccoTEST';
		$user->password = 'testik';
		$user->save();



//		$country = $this->em->find('Country', 2);
//
//		$rental = new \Rental;
//		$rental->country = $country;
//		$rental->status = 'live';
//		$rental->nameUrl = 'live.sk';
//		
//		$user = new \User;
//		$user->login = 'Rado';
//		$user->password = 'tajneheslo';
//		$user->country = $country;
//		$user->active = FALSE;
//		
//		$user->addRental($rental);
//		
//		$this->em->persist($user);
//		$this->em->flush();
	
	}
	
	public function renderAdd() {
		$this->template->form = $this->getComponent('form');
	}

	protected function createComponentForm($name) {
		return new \Tra\Forms\User($this, $name);
	}

}
