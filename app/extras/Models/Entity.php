<?php

namespace Extras\Models;

abstract class Entity extends \Nette\Object implements IEntity, \Nette\Security\IResource, \IteratorAggregate {
	
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
/*
	public function setId() {
		throw new \InvalidArgumentException("Nemozes nastavovat ID");
	}
*/	
	public function getResourceId() {
		return $this->getReflection()->getName();
	}

	public function &__get($name) {
		if ($this->getReflection()->hasProperty($name)) {
			return $this->$name;
		}
		return parent::__get($name);
	}

	public function __set($name, $value) {
		// if ($this->getReflection()->hasProperty($name)) {
		// 	$this->{$name} = $value;
		// 	return;
		// }
		if($name == 'type') debug($name, $value);
		parent::__set($name, $value);
	}
	
	public function getPrimaryKey() {
		$key = $this->getReflection()->getAnnotation('Primary')->id;
		return $this->$key;
	}
	
	public function getPrimaryValue() {
		$key = $this->getReflection()->getAnnotation('Primary')->value;
		return $this->$key;
	}
}
