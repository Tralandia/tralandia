<?php

namespace Extras\Types;

class Email extends BaseType implements IContact {

	public function __construct($email) {
		$this->data = is_array($email) ? array_shift($email) : $email;
	}

	public function toArray() {
		return array('email' => (string) $this);
	}

	public function toFormValue() {
		return 'email~' . (string) $this;
	}

	public function __toString() {
		return $this->data;
	}

	public function encode() {
		return \Nette\Utils\Json::encode($this->toArray());
	}

}