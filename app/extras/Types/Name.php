<?php

namespace Extras\Types;

class Name extends BaseType implements IContact {

	public function __toString() {
		return $this->data;
	}

}