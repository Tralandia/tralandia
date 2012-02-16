<?php

namespace Tra\Services;

use Tra,
	Nette\Application\UI;

class User extends BaseService {

	protected $mainEntity = 'User';
	

	public function prepareForm(UI\Form $form) {
		$user = '\User';
		$reflector = $this->getReflector();
		//$reflector->allow($user, array('id', 'login'));
		//$reflector->allow($user, array('active', 'login', 'password'));
		//$reflector->except($user, array('active'));
		$reflector->extend($form, '\User');
	}

	public function prepareRegistrationForm(UI\Form $form) {
		$user = '\User';
		$reflector = $this->getReflector();
		//$reflector->allow($user, array('id', 'login'));
		//$reflector->allow($user, array('active', 'login', 'password'));
		//$reflector->except($user, array('active'));
		$reflector->extend($form, '\User');
	}

	
	public function create(\Nette\ArrayHash $data) {
		$eUser = new \User($data->User);
		$this->em->persist($eUser);
		$this->em->flush();
		return $eUser;
	}
	
	public function update(\Nette\ArrayHash $data) {
		
	}

	public function delete(array $data = array()) {
		
	}

	public function sendEmail($type = NULL) {
		debug('poslal som email');
	}
}
