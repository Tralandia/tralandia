<?php

namespace Tra\Services;

use Tra;

class Dictionary extends BaseService {

	const MAIN_ENTITY_NAME = '\Dictionary\Phrase';
	
	public function run() {
		
		$translation = new \Dictionary\Translation;
		$type = new \Dictionary\Type;
		$quality = new \Dictionary\Quality;

		debug($translation, $type, $quality);
		debug("RUN SERVICE");
	}
}
