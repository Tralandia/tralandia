<?php

namespace Extras\Types;

class Skype extends BaseType implements IContact {

	public function toArray() {
		return array('skype' => (string) $this);
	}
}