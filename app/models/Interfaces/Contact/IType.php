<?php

namespace Service\Contact;


class Type extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Contact\Type';

	public static function getByClass($class) {
		$repo = self::getEm()->getRepository(self::MAIN_ENTITY_NAME);
		$result = $repo->findOneByClass($class);
		return self::get($result);
	}
	
}
