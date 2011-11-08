<?php

abstract class BaseEntity extends \Nette\Object implements \IteratorAggregate {
	
	public function __construct($data = array()) {
		$this->setData($data);
	}
	
	public function setData($data = array()) {
		if (is_array($data)) {
			$data = \Nette\ArrayHash::from($data);
		}
		
		foreach ($this->getReflection()->getProperties() as $property) {
			if ($data->offsetExists($property->getName()) && $property->getName() != 'id') {
				$this->{$property->getName()} = $data->offsetGet($property->getName());
			}
		}
	}
	
	public function getIterator() {
		return new ArrayIterator($this->toArray());
	}
	
	public function toArray() {
		$arr = array();
		foreach ($this->getReflection()->getProperties() as $property) {
			$arr[$property->getName()] = $this->{$property->getName()};
		}
		return $arr;
	}
	
	public function toFormArray() {
		$arr = array();
		foreach ($this->getReflection()->getProperties() as $property) {
			if ($this->{$property->getName()} instanceof BaseEntity) {
				$arr[$property->getName()] = $this->{$property->getName()}->getId();
			} else {
				$arr[$property->getName()] = $this->{$property->getName()};
			}
		}
		
		return $arr;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId() {
		throw new \InvalidArgumentException("Nemozes nastavovat ID");
	}
	
	public function &__get($name) {
		if ($this->getReflection()->hasProperty($name)) {
			return $this->$name;
		}
		return parent::__get($name);
	}
	
	public function __set($name, $value) {
		if ($this->getReflection()->hasProperty($name) && $name != 'id') {
			$this->{$name} = $value;
			return;
		}
		parent::__set($name, $value);
	}
}