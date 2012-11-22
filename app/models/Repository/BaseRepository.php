<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository {

	public function persist($entity) {
		$this->_em->persist($entity);
	}

	public function flush($entity = NULL) {
		$this->_em->flush($entity);
	}
	
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



	/** 
	 * return array
	 */
	public function getPairs($keyName, $valueName = NULL, $criteria = NULL, $orderBy = NULL, $limit = NULL, $offset = NULL) {
		$serviceList = $this->_getPairs($keyName, $valueName, $criteria, $orderBy, $limit, $offset);
		$return = array();
 
		foreach($serviceList as $item) {
			$key = array_shift($item);
			$value = array_shift($item);
 
			$return[$key] = $value;
		}
 
		return $return;
	}
 
	// /** 
	//  * return array
	//  */
	// public function getTranslatedPairs($keyName, $valueName, $criteria = NULL, $orderBy = NULL, $limit = NULL, $offset = NULL) {
		//  if(isset($orderBy[$valueName])) $orderByName = true;
		//  else $orderByName = false;
		//  $valueName = array($valueName, 'id');
		//  $serviceList = self::_getPairs($keyName, $valueName, $criteria, $orderBy, $limit, $offset);
 
		//  $translator = Service::getTranslator();
		//  $return = array();
		//  foreach($serviceList as $item) {
		// 	$return[$item['key']] = $translator->translate($item['value']);
		//  }
 
		//  if($orderByName) asort($return);
 
		//  return $return;
	// }
  
	protected function _getPairs($keyName, $valueName = NULL, $criteria = NULL, $orderBy = NULL, $limit = NULL, $offset = NULL) {
		$valuePropertyName = NULL;
		$entityName = $this->_entityName;
 
		$qb = $this->_em->createQueryBuilder();
 
		if(is_array($valueName) || $valueName instanceof \Traversable) {
			$valueName = (array) $valueName;
			if(reset($valueName) == 'CONCAT') {
				array_shift($valueName);
				$valueName = call_user_func_array(array($qb->expr(), 'concat'), $valueName);
			} else {
				$valueNameTemp = $valueName;
				$valueName = array_shift($valueNameTemp);
				$valuePropertyName = array_shift($valueNameTemp);
			}
		}
 
		if($valuePropertyName) {
			$select = array("e.$keyName AS key", "p.$valuePropertyName AS value");
		} else if($valueName instanceof \Doctrine\ORM\Query\Expr\Func) {
			$select = array("e.$keyName AS key", "$valueName AS value");
		} else {
			$select = array("e.$keyName AS key", "e.$valueName AS value");
		}
		$qb->select($select)
			->from($entityName, 'e');
 
		if($valuePropertyName) $qb->join('e.'.$valueName, 'p');
 
		if(is_array($criteria) || $criteria instanceof \Traversable) {
			foreach ($this->pripareCriteria($criteria) as $key => $value) {
				if(is_array($value) || $value instanceof \Traversable) {
					$qb->andWhere($qb->expr()->in('e.'.$key, $value));
				} else {
					$qb->andWhere('e.'.$key.' = :'.$key)
						->setParameter($key, $value);
				}
			}
		}
 
 
		if($orderBy) {
			foreach ($orderBy as $key => $value) {
				$qb->addOrderBy('e.'.$key, $value);
			}
		}
		if($limit) $qb->setMaxResults($limit);
		if($offset) $qb->setFirstResult($offset);
  
		return $qb->getQuery()->getResult();
	}

	private function pripareCriteria($criteria) {
		$return = array();
		foreach ($criteria as $key => $value) {
			if($value instanceof \Entity\BaseEntity) {
				$value = $value->id;
			} else if(is_array($value)) {
				$value = $this->pripareCriteria($value);
			}
			$return[$key] = $value;
		}
		return $return;
	}
 
}
