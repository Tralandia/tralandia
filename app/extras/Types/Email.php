<?php

namespace Extras\Types;

class Email extends \Nette\Object {
	
	protected $email;

	public function __construct($email) {
		$this->email = $email;
	}

	public function __toString() {
		return $this->email;
	}

}