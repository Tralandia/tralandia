<?php

namespace Extras\Types;

class Phone extends \Nette\Object {
	
	protected $phone;

	public function __construct($phone) {
		$this->phone = $phone;
	}

	public function __toString() {
		return $this->phone;
	}

}