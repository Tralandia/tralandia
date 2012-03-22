<?php

namespace Extras\Types;

class Latlong extends \Nette\Object {
	
	protected $latlong;

	public function __construct($latlong) {
		$this->latlong = $latlong;
	}

	public function __toString() {
		return $this->latlong;
	}

}