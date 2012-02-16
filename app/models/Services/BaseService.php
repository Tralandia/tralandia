<?php

namespace Tra\Services;

use Entity;

class BaseService extends Service {
	
	private $isPersist = false;

	public function __construct($id = false) {
		if ($id) {
			$this->mainEntity = $this->find($id);
			$this->isPersist = true;
		} else {
			$this->mainEntity = new $this->mainEntityName;
		}
	}

	protected function find($id) {
		return $this->getEm()->find($this->getMainEntityName(), $id);
	}
	
	public function save() {
		if ($this->mainEntity instanceof Entity) {
			if (!$this->isPersist) {
				//$this->getEm()->persist($this->mainEntity);
			}
			$this->getEm()->flush();
		}
	}

	public function delete() {
		if ($this->mainEntity instanceof Entity) {
			$this->getEm()->remove($this->mainEntity);
			$this->getEm()->flush();
		}
	}

	public function getList($class, $key, $value) {
		return $this->em->getRepository($class)->fetchPairs($key, $value);
	}
	
	public function getDataSource() {
		$query = $this->em->createQueryBuilder();
		$query->select('e')->from($this->getMainEntityName(), 'e');
		return $query;
	}
}
