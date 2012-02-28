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
			$entityName = $this->getMainEntityName();
			$this->mainEntity = new $entityName;
		}
	}

	protected function find($id) {
		return $this->em->find($this->getMainEntityName(), $id);
	}
	
	public function save() {
		try {
			if ($this->mainEntity instanceof Entity) {
				if (!$this->isPersist) {
					$this->em->persist($this->mainEntity);
				}
				$this->em->flush();
			}
		} catch (\PDOException $e) {
			throw $e;
		}
	}

	public function delete() {
		if ($this->mainEntity instanceof Entity) {
			$this->em->remove($this->mainEntity);
			$this->em->flush();
		}
	}

	public function getList($class, $key, $value) {debug($this->em);
		return $this->em->getRepository($class)->fetchPairs($key, $value);
	}
	
	public function getDataSource() {
		$query = $this->em->createQueryBuilder();
		$query->select('e')->from($this->getMainEntityName(), 'e');
		return $query;
	}
}
