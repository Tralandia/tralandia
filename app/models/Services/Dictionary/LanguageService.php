<?php

namespace Tra\Services\Dictionary;

use Tra;

class LanguageService extends \Tra\Services\BaseService {

	const MAIN_ENTITY_NAME = '\Dictionary\Language';

	public function isSupported() {
		return (bool)$this->supported;
	}
}