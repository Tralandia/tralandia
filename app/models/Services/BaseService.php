<?php

namespace Services;

use Tra;

abstract class BaseService extends \Nette\Object {
	
	protected $reflector = null;

	public function __construct() {

	}
	
	public function getReflector() {
		if ($this->reflector === null) {
			$this->reflector = new Tra\Reflector\Service();
		}
		return $this->reflector;
	}

}
