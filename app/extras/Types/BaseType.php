<?php

namespace Extras\Types;

class BaseType extends \Nette\Object {
	
	protected $data;

	public function __construct($data) {
		$this->data = $data;
	}

	public function getData() {
		return $this->data;
	}

	public function __toString() {
		return $this->data;
	}

	public function encode() {
		return \Nette\Utils\Json::encode($this->data);
	}

	public function getUnifiedFormat() {
		return (string) $this;
	}
}