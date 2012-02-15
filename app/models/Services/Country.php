<?php

namespace Tra\Services;

use Tra;

class Country extends BaseService {
	
	protected $mainEntity = 'Country';
	
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
