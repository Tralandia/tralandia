<?php

namespace Services\Dictionary;


class LanguageService extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Language';

	public static function getByIso($iso) {
		$repo = self::getEm()->getRepository(self::MAIN_ENTITY_NAME);
		$result = $repo->findOneByIso($iso);
		return self::get($result);
	}
	
}
