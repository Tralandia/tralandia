<?php

namespace Extras\Reflection;

use Nette\Reflection\Property as NProperty;

class Property extends NProperty {

	protected $annotationsParserClass = '\Nette\Reflection\AnnotationsParser';


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



}