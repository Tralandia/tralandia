<?php

namespace Extras\Types;

class Json extends \Nette\Object {
	
	protected $json;

	public function __construct($json) {
		$this->json = $json;
	}

	public function __toString() {
		return $this->json;
	}

}