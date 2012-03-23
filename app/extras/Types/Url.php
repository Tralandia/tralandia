<?php

namespace Extras\Types;

class Url extends \Nette\Object {
	
	protected $url;

	public function __construct($url) {
		$this->url = $url;
	}

	public function __toString() {
		return $this->url;
	}

}