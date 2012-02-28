<?php

namespace Tra\Services;

use Tra;

class Rental extends BaseService {
	
	const MAIN_ENTITY_NAME = 'Rental';
	
	public function getDataSource() {
		$query = $this->em->createQueryBuilder();
		$query->select('3 total, e')
			->from($this->mainEntityName, 'e');
		
		return $query;
	}
/*
	public static function prepareForm($form) {
		$reflector = $this->getReflector();
		//$reflector->allow('\Rental');
		//$reflector->except('\Rental', array('nameUrl'));
		$reflector->extend($form, self::MAIN_ENTITY_NAME);
	}
	
	public function prepareForm($form) {
		$reflector = $this->getReflector();
		//$reflector->allow('\Rental');
		//$reflector->except('\Rental', array('nameUrl'));
		$reflector->extend($form, '\Rental');
	}
*/
	public function gridUpdate($id, $data = array()) {
		$entity = $this->em->find($this->mainEntityName, $id);
		$entity->setData($data->Rental);
		$this->em->flush();
	}
}
