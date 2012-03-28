<?php

namespace Services;


class DomainService extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Domain';
	
	static public function getByDomain($domain) {
		$repo = self::getEm()->getRepository(self::MAIN_ENTITY_NAME);
		$result = $repo->findOneByDomain($domain);
		return self::get($result);
	}

}
