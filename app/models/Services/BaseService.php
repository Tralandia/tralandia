<?php

namespace Tra\Services;

class BaseService extends Service {
	
	public function find($id) {
		return $this->em->find($this->getMainEntity(), $id);
	}
	
	public function get($id) {
		return \Nette\ArrayHash::from(array(
			$this->mainEntity => $this->em->find($this->getMainEntity(), $id)
		));
	}
	
	public function getList($class, $value) {
		return $this->em->getRepository($class)->fetchPairs($class::PRIMARY_KEY, $value);
	}
}
