<?php

class BaseRepository extends Doctrine\ORM\EntityRepository {
	
	public function fetchPairs($key, $value = null) {
		$collection = array();
		foreach ($this->findAll() as $entity) {
			if ($value instanceof Closure) {
				$collection[$entity->$key] = $value($entity);
			} else {
				$collection[$entity->$key] = !empty($value) ? $entity->$value : $entity;
			}
		}
		return $collection;
	}
}