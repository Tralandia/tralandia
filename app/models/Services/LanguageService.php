<?php

namespace Tra\Services;

use Tra;

class LanguageService extends BaseService {

	const MAIN_ENTITY_NAME = '\Language';

	public function isSupported() {
		return (bool)$this->supported;
	}
}