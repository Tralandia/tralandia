<?php

namespace Tra\Services;

use Tra;

class Rental extends BaseService {

	public function prepareFormRental(Tra\Forms\Rental $form) {
		$this->getReflector()->extend($form, '\Rental');
		//$this->getReflector()->extend($form, '\Article');
	}
	
	public function create(\Nette\ArrayHash $data) {
		$eRental = new \Rental($data->Rental);
		$this->em->persist($eRental);
		$this->em->flush();
		debuge($eRental);
	}
	
	public function update(array $data = array()) {
		
	}

	public function delete(array $data = array()) {
		
	}

	public function sendEmail($type = NULL) {
		
	}
}
