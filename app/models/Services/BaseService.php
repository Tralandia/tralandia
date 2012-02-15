<?php

namespace Tra\Services;

class BaseService extends Service {
	
	public function __construct($id = false) {
		if ($id) {
			$this->find($id);
		}
	}

	public function find($id) {
		return $this->em->find($this->getMainEntity(), $id);
	}
	
	public function get($id) {
		return \Nette\ArrayHash::from(array(
			$this->mainEntity => $this->em->find($this->getMainEntity(), $id)
		));
	}
	
	public function getList($class, $key, $value) {
		return $this->em->getRepository($class)->fetchPairs($key, $value);
	}
	
	public function getDataSource() {
		$query = $this->em->createQueryBuilder();
		$query->select('e')->from($this->mainEntity, 'e');
		return $query;
	}
}
