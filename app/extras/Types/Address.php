<?php

namespace Extras\Types;

class Address extends \Nette\Object {
	
	protected $address;

	public function __construct($address) {
		$this->address = $address;
	}

	public function getAddress() {
		return $this->address;
	}

}