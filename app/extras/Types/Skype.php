<?php

namespace Extras\Types;

class Skype extends BaseType implements IContact {

	public function toArray() {
		return array(
			$this->value,
		);
	}

	public function getUnifiedFormat() {
		return (string) $this;
	}
}