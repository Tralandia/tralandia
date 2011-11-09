<?php

namespace Tra\Services;

class Rental extends BaseService {

	public function prepareFormRental(\Forms\Rental $form) {
		$this->getReflector()->extend($form, '\Rental');
		//$this->getReflector()->extend($form, '\Article');
	}
	
	public function prepareData(\Forms\Rental $form) {
		$columns = $this->getReflector()->getAssocationColumns('\Rental');
		$container = $form->getComponent('Rental');
		foreach ($columns as $name => $entity) {
			$control = $container->getComponent($name);
			$entity = $this->em->find($entity, $control->getValue());
			$control->setValue($entity);
			//debug($control);
		}
	}

	public function create(array $data = array()) {
		
	}
	
	public function update(array $data = array()) {
		
	}

	public function delete(array $data = array()) {
		
	}

	public function sendEmail($type = NULL) {
		
	}
}
