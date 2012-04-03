<?php

namespace Services\Contact;


class Type extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Contact\Type';

	public static function getByClass($class) {
		$repo = self::getEm()->getRepository(self::MAIN_ENTITY_NAME);
		$result = $repo->findOneByClass($class);
		return self::get($result);
	}
	
}
