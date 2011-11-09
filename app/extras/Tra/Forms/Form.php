<?php

namespace Tra\Forms;

use Tra;

class Form extends \CoolForm {

	public function getPrepareValues(Tra\Services\Iservice $service) {
		return $service->prepareData($this);
    }
}
