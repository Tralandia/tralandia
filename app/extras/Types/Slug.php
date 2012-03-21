<?php

namespace Extras\Types;

class Slug extends \Nette\Object {
	
	protected $slug;

	public function __construct($slug) {
		$this->slug = $slug;
	}

	public function __toString() {
		return $this->slug;
	}

}