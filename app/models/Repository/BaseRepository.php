<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository {

	// # toto by tu podla mne nemalo bit... @david	
	// public function persist($entity) {
	// 	$this->_em->persist($entity);
	// }
	
	public function remove($entity) {
		$this->_em->remove($entity);
	}
	
	public function getDataSource() {
		$query = $this->_em->createQueryBuilder();
		$query->select('e')->from($this->_entityName, 'e');
		return $query;
	}
	
	// public function findAll() {
	// 	$query = $this->_em->createQueryBuilder();
	// 	$query->select('e')->from($this->_entityName, 'e');
	// 	$query = $query->getQuery();
		
	// 	//$query->setResultCacheDriver(new \Doctrine\Common\Cache\ApcCache());
	// 	//$query->useResultCache(true, 5, 'ooo');
	// 	return $query->getResult();
	// }

	public function fetchPairs($key, $value = null) {
		$collection = array();
		//debug($this->findAll());
		
		foreach ($this->findAll() as $entity) {
			//debug($entity);
			
			
			// if (isset($entity->country))
			// 	debug($entity->country);
		
			// if (isset($entity->rentals))
			// 	debug($entity->rentals);
			
			if ($value instanceof Closure) {
				$collection[$entity->$key] = $value($entity);
			} else {
				$collection[$entity->$key] = !empty($value) ? $entity->$value : $entity;
			}
		}
		
		return $collection;
	}

	public function deleteAll() {
		$query = $this->_em->createQueryBuilder();
		$query->delete($this->_entityName, 'e');
		return $query->getQuery()->execute();
	}
}
