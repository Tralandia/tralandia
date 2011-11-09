<?php

namespace Tra\Services;

abstract class BaseService extends \Nette\Object implements IService {
	
	protected $reflector = null;

	public function __construct() {

	}
	
	public function getReflector() {
		if ($this->reflector === null) {
			$this->reflector = new Reflector($this);
		}
		return $this->reflector;
	}

}
