<?php

namespace Tra\Services;

use Tra;

class Rental extends BaseService {
	
	protected $mainEntity = 'Rental';
	
	public function getDataSource() {
		$query = $this->em->createQueryBuilder();
		$query->select('1 rrr, e, e.id id')->from($this->mainEntity, 'e');
		
		debug($query->getQuery()->getAST()->selectClause->selectExpressions);
		
		foreach ($query->getQuery()->getScalarResult() as $e) {
			debug($e);
		}
		
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
