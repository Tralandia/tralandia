<?php

namespace Extras\Types;

class Price extends \Nette\Object {
	
	protected $price;

	public function __construct($price) {
		$this->price = $price;
	}

	public function __toString() {
		return $this->price;
	}

}