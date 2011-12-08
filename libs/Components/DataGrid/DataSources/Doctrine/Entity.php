<?php

namespace DataGrid\DataSources\Doctrine;

class Entity implements \ArrayAccess, \IteratorAggregate {
	
	const ENTITY_PREFIX = 'e';
		
	protected $row;
	
	public function __construct(&$row, array &$mapper = array()) {
		$this->row = $row;
		$this->mapper = $mapper;
	}
	
	public function __get($key) {
		return $this->offsetGet($key);
	}
	
	public function offsetExists($key) {
		return isset($this->mapper[$key]);
	}
	
	public function offsetGet($key) {
		return $this->getColumnValue($this->row, $key);
	}
	
	public function offsetSet($key, $value) {
		
	}
	
	public function offsetUnset($key) {
		
	}
	
	public function getEntity() {
		return isset($this->row[0]) ? $this->row[0] : null;
	}
	
	private function getColumnValue(&$row, $column) {
		$column = $this->mapper[$column];
		
		if (strpos($column, self::ENTITY_PREFIX . '.') === 0) {
			$column = str_replace('.', '->', substr($column, 2));
			eval('$value = $this->row[0]->' . $column . ';');
			return $value;
		} else {
			return $this->row[$column];
		}
	}
	
	public function getIterator() {
		return new ArrayIterator($this);
	}
}