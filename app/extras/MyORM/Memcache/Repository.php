<?php

namespace Memcache;

use Doctrine\ORM\Mapping\ClassMetadata,
	Doctrine\Common\Persistence\ObjectRepository;

class Repository implements ObjectRepository {
	
	protected $_entityName;
	protected $_em;
	protected $_class;
	
	protected $_mapper = null;

	public function __construct($em, ClassMetadata $class) {
		$this->_entityName = $class->name;
		$this->_em = $em;
		$this->_class = $class;
		
		debug("construct", $this);
		
		$this->_mapper = new Mapper();
		
		//$this->_mapper->write('test', time(), array());
		//debug($this->_mapper->read('test'));
	}

	public function persist(&$entity) {
		$entity->id = $this->getEntityIdentifier($entity);
		$this->_mapper->write($entity->id, $entity, array());
	}
	
	public function remove(&$entity) {
		return $this->_mapper->remove($id);
	}
	
	public function getEntityIdentifier($entity) {
		return spl_object_hash($entity);
    }

	public function find($id) {
		debug("find($id)");
		return $this->_mapper->read($id);
	}

	public function findAll() {
		debug("findAll");
		return array();
	}

	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
		debug("findBy", $criteria, $orderBy, $limit, $offset);
		return array();
	}

	public function findOneBy(array $criteria) {
		debug("findOneBy", $criteria);
		return array();
	}
}