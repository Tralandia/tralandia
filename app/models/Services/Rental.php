<?php

namespace Tra\Services;

use Tra;

class Rental extends BaseService {
	
	protected $mainEntity = 'Rental';
	
	public function getDataSource() {
		$query = $this->em->createQueryBuilder();
		$query->select('e')->from($this->mainEntity, 'e');
		return $query;
	}
	
	public function prepareForm(\Nette\Application\UI\Form $form) {
		$reflector = $this->getReflector();
		//$reflector->allow('\Rental');
		//$reflector->except('\Rental', array('nameUrl'));
		$reflector->extend($form, '\Rental');
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
