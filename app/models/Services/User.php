<?php

namespace Tra\Services;

use Tra;

class User extends BaseService {
	
	public function prepareForm(Tra\Forms\User $form) {
		$this->getReflector()->extend($form, '\User');
	}
	
	public function create(\Nette\ArrayHash $data) {
		$eUser = new \User($data->User);
		$this->em->persist($eUser);
		$this->em->flush();
	}
	
	public function update(\Nette\ArrayHash $data) {
		
	}

	public function delete(array $data = array()) {
		
	}

	public function sendEmail($type = NULL) {
		
	}
}
