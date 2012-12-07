<?php

namespace Extras\Types;

class Latlong extends BaseType {

	public function toFloat() {
		return (float)$this->data;
	}
}