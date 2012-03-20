<?php

namespace Services;

use Entity,
	Tra\Services\ServiceLoader;

class BaseService extends \Tra\Services\Service {
	
	private $isPersist = false;

	public function __construct($new = true) {
		if ($new) {
			$entityName = $this->getMainEntityName();
			$this->mainEntity = new $entityName;
		}
	}

	public static function get($id) {
		$key = get_called_class() . '#' . $id;

		if (ServiceLoader::exists($key)) {
			return ServiceLoader::get($key);
		}
		$service = new static(false);
		$service->load($id);
		ServiceLoader::set($key, $service);
		return $service;
	}

	protected function load($id) {
		if ($entity = $this->getEm()->find($this->getMainEntityName(), $id)) {
			$this->isPersist = true;
			$this->mainEntity = $entity;
		}
	}
	
	public function save() {
		try {
			if ($this->mainEntity instanceof Entity) {
				if (!$this->isPersist) {
					$this->getEm()->persist($this->mainEntity);
				}
				if ($this->isFlushable()) {
					$this->getEm()->flush();
				}
			}
		} catch (\PDOException $e) {
			throw new \Tra\Services\ServiceException($e->getMessage());
		}
	}

	public function delete() {
		try {
			if ($this->mainEntity instanceof Entity) {
				$this->getEm()->remove($this->mainEntity);
				$this->getEm()->flush();
			}
		} catch (\PDOException $e) {
			throw new \Tra\Services\ServiceException($e->getMessage());
		}
	}

	public function getList($class, $key, $value) {
		return $this->getEm()->getRepository($class)->fetchPairs($key, $value);
	}
	
	public function getDataSource() {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from($this->getMainEntityName(), 'e');
		return $query;
	}
}
