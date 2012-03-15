<?php

namespace Services;

use Entity;

class BaseService extends \Tra\Services\Service {
	
	private $isPersist = false;

	public function __construct($id = NULL) {
		if ($id) {
			$this->mainEntity = $this->find($id);
			$this->isPersist = true;
		} else {
			$namespace = $this->getReflection()->getNamespaceName();
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
			throw new \Tra\Services\ServiceException($e->getMessage());
		}
	}

	public function delete() {
		try {
			if ($this->mainEntity instanceof Entity) {
				$this->em->remove($this->mainEntity);
				$this->em->flush();
			}
		} catch (\PDOException $e) {
			throw new \Tra\Services\ServiceException($e->getMessage());
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
