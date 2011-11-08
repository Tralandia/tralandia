<?php

namespace Tra\Service;

class Rental extends BaseService {
	
	private $primaryKey = null;
	
	public function __construct() {
		
	}
	
	public function preapareData(&$data) {
		$data->country = $this->em->find('Country', $data->country);
		$data->user = $this->em->find('User', $data->user);
	}	
	
	public function create($data) {
		// overenie ci mozem create
		
		$rental = new Tra\Entities\Rental;
		$rental->setData($data);
		//$rental->setUser($this->getUser());
		//$rental->setCountry($data->country);
		$this->em->persist($rental);
		$this->em->flush();
	}
	
	public function update($data) {
		// overenie ci mozem update
		
		if (isset($data->status)) {
			$this->changeStatus($data->status);
			unset($data->status);
		}
		
		$rental = $this->em->find('Rental', $this->getPrimaryKey());
		$rental->setData($data);
		//$rental->setUser($this->getUser());
		//$rental->setCountry($data->country);
		$this->em->persist($rental);
		$this->em->flush();
	}
	
	public function changeStatus($status) {
		$rental = $this->em->find('Rental', $this->getPrimaryKey());
		$rental->setStatus($status);
		$this->em->persist($rental);
		$this->em->flush();
	}
}