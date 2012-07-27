<?php

namespace Extras\Reflection;

use Nette\Reflection\ClassType as NClassType;

abstract class ClassType extends NClassType {

	protected $annotationsParserClass = '\Nette\Reflection\AnnotationsParser';
	protected $propertyClass = '\Nette\Reflection\Property';
	protected $methodClass = '\Nette\Reflection\Method';


	public function getAnnotation($name)
	{
		$annotationsParserClass = $this->annotationsParserClass;
		$res = $annotationsParserClass::getAll($this);
		return isset($res[$name]) ? end($res[$name]) : NULL;
	}


	public function getAnnotations()
	{
		$annotationsParserClass = $this->annotationsParserClass;
		return $annotationsParserClass::getAll($this);
	}


	public function getMethods($filter = -1)
	{
		$methodClass = $this->methodClass;
		foreach ($res = parent::getMethods($filter) as $key => $val) {
			$res[$key] = new $methodClass($this->getName(), $val->getName());
		}
		return $res;
	}


	public function getMethod($name)
	{
		$methodClass = $this->methodClass;
		return new $methodClass($this->getName(), $name);
	}


	public function getProperties($filter = -1)
	{
		$propertyClass = $this->propertyClass;
		foreach ($res = parent::getProperties($filter) as $key => $val) {
			$res[$key] = new $propertyClass($this->getName(), $val->getName());
		}
		return $res;
	}
	

	public function getProperty($name) {
		$propertyClass = $this->propertyClass;
		return new $propertyClass($this->getName(), $name);
	}

}