<?php

namespace Extras\Types;

class Email extends BaseType implements IContact {

	public function __toString() {
		return $this->data;
	}

}