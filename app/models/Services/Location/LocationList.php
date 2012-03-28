<?php

namespace Services\Location;

use Extras\Models\ServiceList;

class LocationList extends ServiceList {

	public function prepareList() {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from('Entities\Currency', 'e');
		$this->list = $query->getQuery()->getResult();
	}

	public static function getByType(\Entities\Location\Type $type) {
		$t = new self;
		$repo = self::getEm()->getRepository(self::MAIN_ENTITY_NAME);
		$t->list = $repo->findByType($type);
		return $t;
	}
}