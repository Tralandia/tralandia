<?php

namespace Tra\Services;

use Tra;

class Rental extends BaseService {

	public function prepareForm(Tra\Forms\Rental $form, $dataGrid = NULL) {
		$this->getReflector()->extend($form, '\Rental', $dataGrid);
	}

	public function create(\Nette\ArrayHash $data) {
		$eRental = new \Rental($data->Rental);
		$this->em->persist($eRental);
		$this->em->flush();
	}
	
	public function update(array $data = array()) {
		
	}

	public function delete(array $data = array()) {
		
	}

	public function sendEmail($type = NULL) {
		
	}
}
