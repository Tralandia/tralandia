<?php

namespace Extras\Types;

class Email extends BaseType {

	public function __toString() {
		return $this->data;
	}

}